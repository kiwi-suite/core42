<?php
namespace Core42\Db\RowGateway;

use Zend\Db\RowGateway\AbstractRowGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Core42\Hydrator\ModelHydrator;
use Core42\Model\AbstractModel;

class RowGateway extends AbstractRowGateway
{
    /**
     *
     * @var AbstractModel
     */
    private $model = null;

    /**
     *
     * @var ModelHydrator
     */
    private $hydrator = null;

    /**
     *
     * @param string|array $primaryKeyColumn
     * @param string $table
     * @param string|AbstractModel $modelPrototype
     * @param string $adapterOrSql
     * @param ModelHydrator $hydrator
     * @throws \Zend\Db\RowGateway\Exception\InvalidArgumentException
     */
    public function __construct($primaryKeyColumn, $table, $modelPrototype, $adapterOrSql = null, ModelHydrator $hydrator)
    {
        // setup primary key
        $this->primaryKeyColumn = (array) $primaryKeyColumn;

        // set table
        $this->table = $table;

        // set Sql object
        if ($adapterOrSql instanceof Sql) {
            $this->sql = $adapterOrSql;
        } elseif ($adapterOrSql instanceof Adapter) {
            $this->sql = new Sql($adapterOrSql, $this->table);
        } else {
            throw new \Zend\Db\RowGateway\Exception\InvalidArgumentException('A valid Sql object was not provided.');
        }

        if ($this->sql->getTable() !== $this->table) {
            throw new \Zend\Db\RowGateway\Exception\InvalidArgumentException('The Sql object provided does not have a table that matches this row object');
        }

        if ($modelPrototype instanceof AbstractModel) {
            $this->model = $modelPrototype;
        } elseif (is_string($modelPrototype)) {
            $this->model = new $modelPrototype;
        } else {
            throw new \Zend\Db\RowGateway\Exception\InvalidArgumentException('Invalid model object');
        }

        $this->hydrator = $hydrator;

        $this->initialize();
    }

    /**
     *
     * @return \Core42\Model\AbstractModel
     */
    public function get()
    {
        return $this->model;
    }

    /**
     *
     * @param \Core42\Model\AbstractModel $model
     * @return \Core42\Db\RowGateway\RowGateway
     */
    public function set(AbstractModel $model, $rowExistsInDatabase = false)
    {
        if ($this->model === $model) {
            return $this;
        }

        if (get_class($model) !== get_class($this->model)) {
            throw new \Zend\Db\RowGateway\Exception\InvalidArgumentException('Invalid model object');
        }
        $this->model = $model;
        parent::populate($this->hydrator->extract($this->model), $rowExistsInDatabase);

        return $this;
    }

    /**
     *
     * @see \Zend\Db\RowGateway\AbstractRowGateway::populate()
     */
    public function populate(array $rowData, $rowExistsInDatabase = false)
    {
        parent::populate($rowData, $rowExistsInDatabase);
        $this->model = $this->hydrator->hydrate($this->data, $this->model);
    }

    /**
     *
     * @see \Zend\Db\RowGateway\AbstractRowGateway::save()
     */
    public function save()
    {
        $this->data = $this->hydrator->extract($this->model);
        return parent::save();
    }

    /**
     *
     * @see \Zend\Db\RowGateway\AbstractRowGateway::delete()
     */
    public function delete()
    {
        $this->data = $this->hydrator->extract($this->model);
        $this->processPrimaryKeyData();
        return parent::delete();
    }
}
