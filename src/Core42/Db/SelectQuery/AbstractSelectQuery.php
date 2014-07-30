<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\SelectQuery;

use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\ModelHydrator;
use Core42\Hydrator\Strategy\Database\DatabasePluginManagerInterface;
use Core42\Model\DefaultModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

abstract class AbstractSelectQuery
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var DatabasePluginManagerInterface
     */
    private $hydratorStrategyPluginManager;

    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var bool
     */
    private $isConfigured = false;

    /**
     * @var string
     */
    protected $modelPrototype = '\Core42\Model\DefaultModel';

    /**
     * @var ModelHydrator
     */
    private $hydrator;

    /**
     * @param Adapter $adapter
     * @param DatabasePluginManagerInterface $hydratorStrategyPluginManager
     */
    public function __construct(Adapter $adapter, DatabasePluginManagerInterface $hydratorStrategyPluginManager)
    {
        $this->adapter = $adapter;

        $this->sql = new Sql($adapter);

        $this->hydratorStrategyPluginManager = $hydratorStrategyPluginManager;
    }

    /**
     * @return Sql
     */
    protected function getSql()
    {
        return $this->sql;
    }

    /**
     * @return Adapter
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return DatabasePluginManagerInterface
     */
    protected function getHydratorStrategyPluginManager()
    {
        return $this->hydratorStrategyPluginManager;
    }

    /**
     * @return DefaultModel
     */
    protected function getModel()
    {
        return new $this->modelPrototype;
    }

    /**
     * @return ModelHydrator
     */
    protected function getHydrator()
    {
        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }

        return $this->hydrator;
    }

    /**
     * @return mixed
     */
    abstract public function getSelect();

    /**
     *
     */
    protected function configure()
    {

    }

    /**
     * @return ResultSet
     */
    public function getResultSet()
    {
        if ($this->isConfigured === false) {
            $this->configure();
            $this->isConfigured =  true;
        }

        $select = $this->getSelect();

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
}
