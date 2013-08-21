<?php
namespace Core42\Db\RowGateway;

use Zend\Db\RowGateway\AbstractRowGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Core42\Hydrator\ModelHydrator;
use Core42\Model\AbstractModel;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

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
     */
    public function __get($name){}

    /**
     *
     */
    public function __set($name, $value){}

    /**
     *
     */
    public function __isset($name){}

    /**
     *
     */
    public function __unset($name){}

    /**
     *
     * @param string $name
     * @param StrategyInterface $strategy
     * @return \Core42\Db\RowGateway\RowGateway
     */
    public function addHydratorStrategy($name, StrategyInterface $strategy)
    {
        $this->hydrator->addStrategy($name, $strategy);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return \Core42\Db\RowGateway\RowGateway
     */
    public function removeHydratorStrategy($name)
    {
        $this->hydrator->removeStrategy($name);
        return $this;
    }

    /**
     *
     * @return \Core42\Db\Model\AbstractModel
     */
    public function get()
    {
        return $this->model;
    }

    /**
     *
     * @param \Core42\Db\Model\AbstractModel $model
     * @return \Core42\Db\RowGateway\RowGateway
     */
    public function set(AbstractModel $model)
    {
        if (get_class($model) !== get_class($this->model)) {
            throw new \Zend\Db\RowGateway\Exception\InvalidArgumentException('Invalid model object');
        }
        $this->populate($this->hydrator->extract($model), $this->rowExistsInDatabase());

        return $this;
    }

    public function populate($rowData, $rowExistsInDatabase = false)
    {
        parent::populate($rowData, $rowExistsInDatabase);

        $modelPrototype = get_class($this->model);
        $model = new $modelPrototype();

        $this->model = $this->hydrator->hydrate($this->data, $model);
    }

    public function save()
    {
        $this->data = $this->hydrator->extract($this->model);
        parent::save();
    }

    public function delete()
    {
        $this->data = $this->hydrator->extract($this->model);
        parent::delete();
    }
}