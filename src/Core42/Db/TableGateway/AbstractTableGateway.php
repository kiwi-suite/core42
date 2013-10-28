<?php
namespace Core42\Db\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Hydrator\ModelHydrator;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;
use Core42\Model\AbstractModel;
use Zend\Db\Metadata\Metadata;
use Core42\Db\TableGateway\Feature\HydratorFeature;
use Core42\Db\TableGateway\Feature\RowGatewayFeature;
use Core42\Db\RowGateway\RowGateway;
use Core42\Db\TableGateway\Feature\MetadataFeature;

abstract class AbstractTableGateway extends ZendAbstractTableGateway
{
    /**
     *
     * @var string
     */
    private $rowGatewayDefinition = '\Core42\Db\RowGateway\RowGateway';

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
     * @var array
     */
    private static $instance = array();

    /**
     *
     * @var ModelHydrator
     */
    protected $hydrator;

    /**
     *
     * @var \Core42\Db\RowGateway\RowGateway
     */
    protected $rowGatewayPrototype;

    /**
     *
     * @var Metadata
     */
    protected $metadata;

    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    /**
     *
     */
    protected function __construct()
    {
        $this->adapter = $this->getServiceManager()->get('Db\Master');
        $slave = $this->adapter;
        if ($this->getServiceManager()->has('Db\Slave')) {
            $slave = $this->getServiceManager()->get('Db\Slave');
        }

        $this->metadata = new Metadata($this->adapter);

        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }

        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new MasterSlaveFeature($slave));
        $this->featureSet->addFeature(new MetadataFeature($this->metadata));
        $this->featureSet->addFeature(new RowGatewayFeature($this->rowGatewayDefinition, $this->modelPrototype, $this->hydrator));
        $this->featureSet->addFeature(new HydratorFeature($this->metadata, $this->getServiceManager()));

        $this->initialize();
    }

    /**
     *
     * @param ServiceManager $manager
     */
    public static function setServiceManager(ServiceManager $manager)
    {
        self::$serviceManager = $manager;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     *
     * @return \Core42\Db\TableGateway\AbstractTableGateway
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!array_key_exists($className, self::$instance)) {
            self::$instance[$className] = new $className;
        }

        return self::$instance[$className];
    }

    /**
     *
     * @param RowGateway $rowGateway
     */
    public function setRowGateway(RowGateway $rowGateway)
    {
        $this->rowGatewayPrototype = $rowGateway;
    }

    /**
     *
     * @return \Core42\Db\RowGateway\RowGateway
     */
    public function getRowGateway()
    {
        return clone $this->rowGatewayPrototype;
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
     *
     * @see \Zend\Db\TableGateway\AbstractTableGateway::insert()
     */
    public function insert($set)
    {
        if ($set instanceof AbstractModel) {
            $rowGateway = $this->getRowGateway();
            $rowGateway->set($set, false);
            return $rowGateway->save();
        }
        return parent::insert($set);
    }

    /**
     *
     * @see \Zend\Db\TableGateway\AbstractTableGateway::update()
     */
    public function update($set, $where = null)
    {
        if ($set instanceof AbstractModel && $where === null) {
            $rowGateway = $this->getRowGateway();
            $rowGateway->set($set, true);
            return $rowGateway->save();
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
            $rowGateway = $this->getRowGateway();
            $rowGateway->set($where);
            return $rowGateway->delete();
        }
        return parent::delete($where);
    }

    /**
     *
     * @param string|int|array $values
     * @return \Core42\Model\AbstractModel|null
     */
    public function selectByPrimary($values)
    {
        if (!is_array($values) && !is_int($values) && !is_string($values)) {
            throw new \Exception("invalid value");
        }

        $metadata = $this->getFeatureSet()->getFeatureByClassName('Core42\Db\TableGateway\Feature\MetadataFeature');
        $primary = $metadata->getPrimaryKey();

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

    public function getSqlColumns()
    {
        $sqlColumns = array();
        foreach ($this->getColumns() as $column){
            $sqlColumns["{$this->table}.{$column}"] = $column;
        }
        return $sqlColumns;
    }
}
