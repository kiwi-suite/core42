<?php
namespace Core42\Db\TableGateway;

use Core42\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Hydrator\ModelHydrator;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;
use Core42\Model\AbstractModel;
use Zend\Db\Metadata\Metadata;
use Core42\Db\TableGateway\Feature\HydratorFeature;
use Core42\Db\TableGateway\Feature\MetadataFeature;

abstract class AbstractTableGateway extends ZendAbstractTableGateway
{
    /**
     *
     * @var string
     */
    protected $table = '';

    /**
     *
     * @var string|AbstractModel
     */
    protected $modelPrototype = null;

    /**
     *
     * @var ModelHydrator
     */
    protected $hydrator;

    /**
     *
     * @var Metadata
     */
    protected $metadata;

    /**
     *
     */
    public function __construct(Adapter $adapter, Adapter $slave, Metadata $metadata, $hydratorStrategyPluginManager)
    {
        $this->adapter = $adapter;

        $this->metadata = $metadata;

        if (is_string($this->modelPrototype)) {
            $className = $this->modelPrototype;
            $this->modelPrototype = new $className;
        }

        if (!($this->modelPrototype instanceof AbstractModel)) {
            throw new \Exception("invalid model prototype");
        }

        $this->hydrator = new ModelHydrator();

        $this->resultSetPrototype = new ResultSet($this->hydrator, $this->modelPrototype);

        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new MasterSlaveFeature($slave));
        $this->featureSet->addFeature(new MetadataFeature($this->metadata));
        $this->featureSet->addFeature(new HydratorFeature($this->metadata, $hydratorStrategyPluginManager));

        $this->initialize();
    }

    /**
     * @return AbstractModel|string
     */
    public function getModelPrototype()
    {
        return $this->modelPrototype;
    }

    /**
     * @return \Core42\Hydrator\ModelHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param AbstractModel|array $set
     * @return int
     */
    public function insert($set)
    {
        if ($set instanceof AbstractModel) {
            $insertSet = $this->getHydrator()->extract($set);
            $result = parent::insert($insertSet);
            if (($primaryKeyValue = $this->lastInsertValue) && count($this->getPrimaryKey()) == 1) {
                $where = array_flip($this->getPrimaryKey());
                reset($where);
                $where[key($where)] = $primaryKeyValue;
            } else {
                $where = $this->getPrimaryValues($set);
            }
            $tmpObject = $this->selectByPrimary($where);
            $this->getHydrator()->hydrate($this->getHydrator()->extract($tmpObject), $set);
            $set->memento();

            return $result;
        } elseif (is_array($set)) {
            $set = array_intersect_key($this->getHydrator()->extract($this->getModelPrototype()->getHydrator()->hydrate($set, $this->getModelPrototype())), $set);
        }
        if (empty($set)) {
            return 0;
        }

        return parent::insert($set);
    }

    /**
     *
     * @see \Zend\Db\TableGateway\AbstractTableGateway::update()
     */
    public function update($set, $where = null)
    {
        if ($set instanceof AbstractModel) {
            $where = $this->getPrimaryValues($set);
            if (empty($where)) {
                throw new \Exception("no primary key set");
            }

            $values = $this->getHydrator()->extract($set);
            $set = array_intersect_key($values, $set->diff());
        } elseif (is_array($set)) {
            $set = array_intersect_key($this->getHydrator()->extract($this->getModelPrototype()->getHydrator()->hydrate($set, $this->getModelPrototype())), $set);
            if (is_array($where)) {
                $where = array_intersect_key($this->getHydrator()->extract($this->getModelPrototype()->getHydrator()->hydrate($where, $this->getModelPrototype())), $where);
            }
        }
        if (empty($set)) {
            return 0;
        }

        return parent::update($set, $where);
    }

    /**
     *
     * @see \Zend\Db\TableGateway\AbstractTableGateway::delete()
     */
    public function delete($where)
    {
        if ($where instanceof AbstractModel) {
            $where = $this->getPrimaryValues($where);
            if (empty($where)) {
                return 0;
            }
        } elseif (is_array($where)) {
            $where = array_intersect_key($this->getHydrator()->extract($this->getHydrator()->hydrate($where, $this->getModelPrototype())), $where);
        }

        return parent::delete($where);
    }

    /**
     *
     * @param  string|int|array                 $values
     * @return \Core42\Model\AbstractModel|null
     * @throws \Exception
     */
    public function selectByPrimary($values)
    {
        if (!is_array($values) && !is_int($values) && !is_string($values)) {
            throw new \Exception("invalid value");
        }

        $primary = $this->getPrimaryKey();

        if ((!is_array($values) && count($primary) != 1) || count($values) != count($primary)) {
            throw new \Exception("invalid value");
        }

        if (!is_array($values)) {
            $values = array($primary[0] => $values);
        }

        if (count(array_diff(array_keys($values), $primary)) > 0) {
            throw new \Exception("invalid value");
        }

        $resultSet = $this->select($values);
        if ($resultSet->count() == 0) {
            return null;
        }

        return $resultSet->current();
    }

    /**
     * @return array
     */
    public function getPrimaryKey()
    {
        $metadata = $this->getFeatureSet()->getFeatureByClassName('Core42\Db\TableGateway\Feature\MetadataFeature');

        return $metadata->getPrimaryKey();
    }

    /**
     * @param  AbstractModel $model
     * @return array
     */
    public function getPrimaryValues(AbstractModel $model)
    {
        $values = $this->getHydrator()->extract($model);

        $values = array_intersect_key($values, array_flip($this->getPrimaryKey()));

        return array_filter($values, function ($var) {
            return !empty($var);
        });
    }

    /**
     * @return array
     */
    public function getSqlColumns()
    {
        $sqlColumns = array();
        foreach ($this->getColumns() as $column) {
            $sqlColumns["{$this->table}.{$column}"] = $column;
        }

        return $sqlColumns;
    }
}
