<?php
namespace Core42\Db\SqlQuery;

use Core42\Hydrator\ModelHydrator;
use Zend\Db\ResultSet\AbstractResultSet;
use Zend\ServiceManager\ServiceManager;
use Core42\ServiceManager\ServiceManagerStaticAwareInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\Sql\Select;
use Core42\Db\ResultSet\ResultSet;
use Core42\Model\DefaultModel;
use Core42\Model\AbstractModel;

class SqlQuery implements ServiceManagerStaticAwareInterface
{
    /**
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    private $adapterMaster;

    /**
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    private $adapterSlave;

    /**
     *
     * @var Sql
     */
    private $sql;

    /**
     * @var AbstractModel
     */
    private $model;

    /**
     * @var AbstractResultSet
     */
    private $resultSet;

    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    public function __construct()
    {
        $this->adapterMaster = $this->getServiceManager()->get('Db\Master');
        $this->adapterSlave = $this->adapterMaster;
        if ($this->getServiceManager()->has('Db\Slave')) {
            $this->adapterSlave = $this->getServiceManager()->get('Db\Slave');
        }
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
     * @param  string|array        $table
     * @return \Zend\Db\Sql\Select
     */
    public function select($table = null)
    {
        $this->sql = new Sql($this->adapterSlave);

        return $this->sql->select($table);
    }

    /**
     *
     * @param  string|array        $table
     * @return \Zend\Db\Sql\Insert
     */
    public function insert($table = null)
    {
        $this->sql = new Sql($this->adapterMaster);

        return $this->sql->insert($table);
    }

    /**
     *
     * @param  string|array        $table
     * @return \Zend\Db\Sql\Update
     */
    public function update($table = null)
    {
        $this->sql = new Sql($this->adapterMaster);

        return $this->sql->update($table);
    }

    /**
     *
     * @param  string|array        $table
     * @return \Zend\Db\Sql\Delete
     */
    public function delete($table = null)
    {
        $this->sql = new Sql($this->adapterMaster);

        return $this->sql->delete($table);
    }

    /**
     * @param  AbstractModel                $model
     * @return \Core42\Db\SqlQuery\SqlQuery
     */
    public function setModel(AbstractModel $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return AbstractModel
     */
    protected function getModel()
    {
        if (!($this->model instanceof AbstractModel)) {
            $this->model = new DefaultModel();
        }

        return $this->model;
    }

    /**
     * @param  AbstractResultSet            $resultSet
     * @return \Core42\Db\SqlQuery\SqlQuery
     */
    public function setResultSet(AbstractResultSet $resultSet)
    {
        $this->resultSet = $resultSet;

        return $this;
    }

    /**
     * @return AbstractResultSet
     */
    public function getResultSet()
    {
        if (!($this->resultSet instanceof AbstractResultSet)) {
            $this->resultSet = new ResultSet(new ModelHydrator(), $this->getModel());
        }

        return $this->resultSet;
    }

    /**
     *
     * @param  PreparableSqlInterface $prepareableSql
     * @throws \Exception
     * @internal param \Core42\Model\AbstractModel  $model
     * @return \Core42\Db\ResultSet\ResultSet
     */
    public function execute(PreparableSqlInterface $prepareableSql)
    {
        if (!($this->sql instanceof Sql)) {
            throw new \Exception();
        }
        $return = null;

        $statement = $this->sql->prepareStatementForSqlObject($prepareableSql);
        $result = $statement->execute();

        if ($prepareableSql instanceof Select) {
            $resultSet = $this->getResultSet();
            $resultSet->initialize($result);
            $return = $resultSet;
        }

        $this->sql = null;
        $this->model = null;
        $this->resultSet = null;

        return $return;
    }
}
