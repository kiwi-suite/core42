<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Selector;

use Core42\Db\ResultSet\ResultSet;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\DatabaseHydrator;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Core42\Model\GenericModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

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

    protected function init()
    {
        parent::init();

        if (!is_array($this->getDatabaseTypeMap())) {
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
     * @return HydratorStrategyPluginManager
     */
    protected function getHydratorStrategyManager()
    {
        return $this->getServiceManager()->get('Core42\HydratorStrategyPluginManager');
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
     * @return DatabaseHydrator
     */
    protected function getHydrator()
    {
        if ($this->hydrator instanceof HydratorInterface) {
            return $this->hydrator;
        }
        $this->hydrator = new DatabaseHydrator();

        foreach ($this->getDatabaseTypeMap() as $name => $strategy) {
            $this->hydrator->addStrategy(
                $name,
                $this->getHydratorStrategyManager()->get($strategy)
            );
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

        if (is_string($select)) {
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
