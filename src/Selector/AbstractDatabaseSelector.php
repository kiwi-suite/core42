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


namespace Core42\Selector;

use Core42\Db\ResultSet\ResultSet;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\BaseHydrator;
use Core42\Model\GenericModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\HydratorInterface;

abstract class AbstractDatabaseSelector extends AbstractSelector
{
    /**
     * @var string
     */
    protected $dbAdapterServiceName = 'Db\Master';

    /**
     * @var Sql
     */
    protected $sql;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @throws \Exception
     */
    protected function init()
    {
        parent::init();

        if (!\is_array($this->getDatabaseTypeMap())) {
            throw new \Exception("'getDatabaseTypeMap' doesn't return an array");
        }
    }

    /**
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    protected function getTableGateway($tableGatewayName)
    {
        return $this->getServiceManager()->get('TableGateway')->get($tableGatewayName);
    }

    /**
     * @return Select|string|ResultSet
     */
    abstract protected function getSelect();

    /**
     * @return Sql
     */
    protected function getSql()
    {
        if ($this->sql === null) {
            $this->sql = new Sql($this->getAdapter());
        }

        return $this->sql;
    }

    /**
     * @return GenericModel
     */
    protected function getModel()
    {
        return new GenericModel();
    }

    /**
     * @return BaseHydrator
     */
    protected function getHydrator()
    {
        if (!($this->hydrator instanceof HydratorInterface)) {
            $this->hydrator = $this->getServiceManager()->get('HydratorManager')->get(BaseHydrator::class);
            $this->hydrator->addStrategies($this->getDatabaseTypeMap());
        }

        return $this->hydrator;
    }

    /**
     * @return array
     */
    protected function getDatabaseTypeMap()
    {
        return [];
    }

    /**
     * @return ResultSet
     */
    public function getResult()
    {
        $select = $this->getSelect();

        if ($select instanceof ResultSet) {
            return $select;
        }

        $resultSet = new ResultSet($this->getHydrator(), $this->getModel());

        if (\is_string($select)) {
            $resultSet = $this->getAdapter()->query($select, Adapter::QUERY_MODE_EXECUTE, $resultSet);
        } elseif ($select instanceof Select) {
            $statement = $this->getSql()->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet->initialize($result);
        }

        return $resultSet;
    }

    /**
     * @return Adapter
     */
    protected function getAdapter()
    {
        return $this->getServiceManager()->get($this->dbAdapterServiceName);
    }
}
