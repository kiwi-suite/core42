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
use Core42\Hydrator\ModelHydrator;
use Core42\Model\GenericModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

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
     * @return Select|string|ResultSet
     */
    abstract public function getSelect();

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
     * @return ModelHydrator
     */
    protected function getHydrator()
    {
        return new ModelHydrator();
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
