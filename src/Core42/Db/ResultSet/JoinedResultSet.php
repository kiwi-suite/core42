<?php
namespace Core42\Db\ResultSet;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\AbstractResultSet;

class JoinedResultSet extends AbstractResultSet
{
    private $tableGateways = array();

    public function __construct(array $tableGateways)
    {
        foreach ($tableGateways as $key => $tableGateway) {
            if (!($tableGateway instanceof AbstractTableGateway)) {
                throw new \Exception("invalid param");
            }
            if (!is_string($key)) {
                $key = $tableGateway->getTable();
            }
            $this->tableGateways[$key] = $tableGateway;
        }
    }

    /**
     * @return array
     */
    public function current()
    {
        if ($this->buffer === null) {
            $this->buffer = -2; // implicitly disable buffering from here on
        } elseif (is_array($this->buffer) && isset($this->buffer[$this->position])) {
            return $this->buffer[$this->position];
        }
        $data = $this->dataSource->current();
        $preparedData = array();

        foreach ($data as $name => $value) {
            if (strpos($name, ".") !== false) {
                list($aliasName, $column) = explode(".", $name);
                $preparedData[$aliasName][$column] = $value;
            }
        }

        $array = array();
        foreach ($this->tableGateways as $keyName => $tableGateway) {
            if (!isset($preparedData[$keyName]) || !is_array($preparedData[$keyName])) {
                $array[$keyName] = false;
                continue;
            }
            $model = $tableGateway->getModelPrototype();
            if (is_string($model)) {
                $model = new $model();
            }
            $array[$keyName] = $tableGateway->getHydrator()->hydrate($preparedData[$keyName], clone $model);
        }

        if (is_array($this->buffer)) {
            $this->buffer[$this->position] = $array;
        }

        return $array;
    }
}
