<?php
namespace Core42\Db\SqlQuery;


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
     * @param string $table
     * @return \Zend\Db\Sql\Select
     */
    public function select($table = null)
    {
        $this->sql =  new Sql($this->adapterSlave);
        return $this->sql->select($table);
    }

    /**
     *
     * @param string $table
     * @return \Zend\Db\Sql\Insert
     */
    public function insert($table = null)
    {
        $this->sql =  new Sql($this->adapterMaster);
        return $this->sql->insert($table);
    }

    /**
     *
     * @param string $table
     * @return \Zend\Db\Sql\Update
     */
    public function update($table = null)
    {
        $this->sql =  new Sql($this->adapterMaster);
        return $this->sql->update($table);
    }

    /**
     *
     * @param string $table
     * @return \Zend\Db\Sql\Delete
     */
    public function delete($table = null)
    {
        $this->sql =  new Sql($this->adapterMaster);
        return $this->sql->delete($table);
    }

    /**
     *
     * @param PreparableSqlInterface $prepareableSql
     * @param AbstractModel $model
     * @throws \Exception
     * @return \Core42\Db\ResultSet\ResultSet
     */
    public function execute(PreparableSqlInterface $prepareableSql, AbstractModel $model = null)
    {
        if (!($this->sql instanceof Sql)) {
            throw new \Exception();
        }

        $statement = $this->sql->prepareStatementForSqlObject($prepareableSql);
        $result = $statement->execute();
        $this->sql = null;

        if ($prepareableSql instanceof Select) {
            if ($model === null) {
                $model = new DefaultModel();
            }
            $resultSet = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $model);
            $resultSet->initialize($result);
            return $resultSet;
        }

    }
}
