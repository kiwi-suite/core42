<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Db\TableGateway;

use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\BaseHydrator;
use Core42\Model\ModelInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;

abstract class AbstractTableGateway extends ZendAbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string|ModelInterface
     */
    protected $modelPrototype = null;

    /**
     * @var array
     */
    protected $databaseTypeMap = [];

    /**
     * @var BaseHydrator
     */
    protected $hydrator;

    /**
     * @var bool
     */
    protected $underscoreSeparatedKeys = false;

    /**
     * @var array
     */
    protected $primaryKey = [];

    /**
     * @param Adapter $adapter
     * @param Adapter $slave
     * @param BaseHydrator $hydrator
     * @throws \Exception
     */
    public function __construct(
        Adapter $adapter,
        BaseHydrator $hydrator,
        Adapter $slave = null
    ) {
        $this->adapter = $adapter;

        if (\is_string($this->modelPrototype)) {
            $className = $this->modelPrototype;
            $this->modelPrototype = new $className();
        }

        if (!($this->modelPrototype instanceof ModelInterface)) {
            throw new \Exception('invalid model prototype');
        }

        $this->hydrator = $hydrator;
        $this->hydrator->addStrategies($this->databaseTypeMap);

        $this->resultSetPrototype = new ResultSet($this->hydrator, $this->modelPrototype);

        $this->featureSet = new FeatureSet();
        if ($slave !== null) {
            $this->featureSet->addFeature(new MasterSlaveFeature($slave));
        }
    }

    /**
     * @return ModelInterface|string
     */
    public function getModelPrototype()
    {
        return $this->modelPrototype;
    }

    /**
     * @return ModelInterface
     */
    public function getModel()
    {
        if (\is_object($this->modelPrototype)) {
            return clone $this->modelPrototype;
        }

        return new $this->modelPrototype();
    }

    /**
     * @return BaseHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @return array
     */
    public function getDatabaseTypeMap()
    {
        return $this->databaseTypeMap;
    }

    /**
     * @param ModelInterface|array $set
     * @return int
     */
    public function insert($set)
    {
        if ($set instanceof ModelInterface) {
            $insertSet = $this->getHydrator()->extract($set);
            $result = parent::insert($insertSet);
            if (($primaryKeyValue = $this->lastInsertValue) && \count($this->getPrimaryKey()) == 1) {
                $where = \array_flip($this->getPrimaryKey());
                \reset($where);
                $where[\key($where)] = $primaryKeyValue;
            } else {
                $where = $this->getPrimaryValues($set);
            }
            $tmpObject = $this->selectByPrimary($where);
            $this->getHydrator()->hydrate($this->getHydrator()->extract($tmpObject), $set);
            $set->memento();

            return $result;
        } elseif (\is_array($set)) {
            $set = $this->getHydrator()->extractArray($set);
        }
        if (empty($set)) {
            return 0;
        }

        return parent::insert($set);
    }

    /**
     * @see \Zend\Db\TableGateway\AbstractTableGateway::update()
     * @param mixed $set
     * @param null|mixed $where
     */
    public function update($set, $where = null)
    {
        if ($set instanceof ModelInterface) {
            $where = $this->getPrimaryValues($set);
            if (empty($where)) {
                throw new \Exception('no primary key set');
            }

            $updateSet = $this->getHydrator()->extractArray($set->diff());
            $set->memento();
        } elseif (\is_array($set)) {
            $updateSet = $this->getHydrator()->extractArray($set);

            if (\is_array($where)) {
                $where = $this->getHydrator()->extractArray($where);
            }
        }
        if (empty($updateSet)) {
            return 0;
        }

        return parent::update($updateSet, $where);
    }

    /**
     * @see \Zend\Db\TableGateway\AbstractTableGateway::delete()
     * @param mixed $where
     */
    public function delete($where)
    {
        if ($where instanceof ModelInterface) {
            $where = $this->getPrimaryValues($where);
            if (empty($where)) {
                return 0;
            }
        } elseif (\is_array($where)) {
            $where = $this->getHydrator()->extractArray($where);
        }

        return parent::delete($where);
    }

    /**
     * @param  string|int|array                 $values
     * @throws \Exception
     * @return \Core42\Model\AbstractModel|null
     */
    public function selectByPrimary($values)
    {
        if (!\is_array($values) && !\is_int($values) && !\is_string($values)) {
            throw new \Exception('invalid value');
        }

        $primary = $this->getPrimaryKey();

        if ((!\is_array($values) && \count($primary) != 1) || \count($values) != \count($primary)) {
            throw new \Exception('invalid value');
        }

        if (!\is_array($values)) {
            $values = [$primary[0] => $values];
        }

        if (\count(\array_diff(\array_keys($values), $primary)) > 0) {
            throw new \Exception('invalid value');
        }

        $resultSet = $this->select($values);
        if ($resultSet->count() == 0) {
            return;
        }

        return $resultSet->current();
    }

    /**
     * @return array
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param  ModelInterface $model
     * @return array
     */
    public function getPrimaryValues(ModelInterface $model)
    {
        $values = $this->getHydrator()->extract($model);

        $values = \array_intersect_key($values, \array_flip($this->getPrimaryKey()));

        return \array_filter($values, function ($var) {
            return !empty($var);
        });
    }

    /**
     * @return array
     */
    public function getSqlColumns()
    {
        $sqlColumns = [];
        foreach ($this->getColumns() as $column) {
            $sqlColumns["{$this->table}.{$column}"] = $column;
        }

        return $sqlColumns;
    }

    /**
     * @param ModelInterface $model
     * @throws \Exception
     */
    public function refresh(ModelInterface $model)
    {
        $where = $this->getPrimaryValues($model);
        $tmpObject = $this->selectByPrimary($where);
        $this->getHydrator()->hydrate($this->getHydrator()->extract($tmpObject), $model);
        $model->memento();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return \array_keys($this->databaseTypeMap);
    }
}
