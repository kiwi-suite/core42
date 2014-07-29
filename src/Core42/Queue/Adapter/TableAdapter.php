<?php
namespace Core42\Queue\Adapter;

use Core42\Queue\Job;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Json\Json;

class TableAdapter implements AdapterInterface
{
    /**
     * @var Adapter
     */
    private $adpater;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var bool
     */
    private $initialized = false;

    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function setOptions(array $options = array())
    {
        if (!array_key_exists('db_adapter', $options)) {
            throw new \Exception("db_adapter not defined in options");
        }
        $this->adpater = $options['db_adapter'];

        if (!($this->adpater instanceof Adapter)) {
            throw new \Exception("adapter isn't an instance of Zend\\Db\\Adapter\\Adapter");
        }

        if (!array_key_exists('table_name', $options)) {
            throw new \Exception("table_name not defined in options");
        }
        $this->tableName = (string)$options['table_name'];
    }

    protected function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        $this->initialized = true;

        $metadata = new Metadata($this->adpater);
        if (in_array($this->tableName, $metadata->getTableNames())) {
            return;
        }

        $sql = "CREATE TABLE {$this->tableName}(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `serviceName` VARCHAR(255) NOT NULL,
  `params` TEXT NOT NULL,
  PRIMARY KEY (`id`))";

        $this->adpater->query(
            $sql,
            Adapter::QUERY_MODE_EXECUTE
        );
    }

    /**
     * @param Job $job
     */
    public function push(Job $job)
    {
        $this->initialize();

        $insert = new Insert($this->tableName);

        $insert->columns(array('serviceName', 'params'));
        $insert->values(array(
            'serviceName' => $job->getServiceName(),
            'params' => Json::encode($job->getParams()),
        ));

        $sql = new Sql($this->adpater);

        $this->adpater->query(
            $sql->getSqlStringForSqlObject($insert),
            Adapter::QUERY_MODE_EXECUTE
        );
    }

    /**
     * @return Job|null
     */
    public function pop()
    {
        $this->initialize();

        $select = new Select($this->tableName);
        $select->columns(array('id', 'serviceName', 'params'));
        $select->limit(1);
        $select->order('id ASC');

        $sql = new Sql($this->adpater);

        $resultSet = $this->adpater->query(
            $sql->getSqlStringForSqlObject($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        $res = $resultSet->current();
        if (empty($res)) {
            return null;
        }

        $delete = new Delete($this->tableName);
        $delete->where(array('id' => $res['id']));

        $this->adpater->query(
            $sql->getSqlStringForSqlObject($delete),
            Adapter::QUERY_MODE_EXECUTE
        );

        $job = new Job();
        $job->setServiceName($res['serviceName']);
        $job->setParams(Json::decode($res['params'], Json::TYPE_ARRAY));

        return $job;
    }


    /**
     * @return int
     */
    public function count()
    {
        $this->initialize();

        $select = new Select($this->tableName);
        $select->columns(array(
            'count' => new Expression('COUNT(*)'),
        ));

        $sql = new Sql($this->adpater);
        $resultSet = $this->adpater->query(
            $sql->getSqlStringForSqlObject($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        $res = $resultSet->current();

        return (int)$res['count'];
    }
}
