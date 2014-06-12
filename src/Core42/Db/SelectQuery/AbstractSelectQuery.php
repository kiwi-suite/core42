<?php
namespace Core42\Db\SelectQuery;

use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\ModelHydrator;
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
     * @var Sql
     */
    private $sql;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;

        $this->sql = new Sql($adapter);
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
     * @return DefaultModel
     */
    protected function getModel()
    {
        return new DefaultModel();
    }

    /**
     * @return mixed
     */
    abstract public function getSelect();

    /**
     * @return ResultSet
     */
    public function getResultSet()
    {
        $select = $this->getSelect();

        $modelHydrator = new ModelHydrator();

        $resultSet = new ResultSet($modelHydrator, $this->getModel());

        if (is_string($select)) {
            $resultSet = $this->getAdapter()->query($select, Adapter::QUERY_MODE_EXECUTE, $resultSet);
        } else if ($select instanceof Select) {
            $statement = $this->getSql()->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet->initialize($result);
        }

        return $resultSet;
    }
}
