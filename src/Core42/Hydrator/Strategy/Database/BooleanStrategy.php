<?php
namespace Core42\Hydrator\Strategy\Database;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Core42\Db\DataConverter\DataConverter;

class BooleanStrategy implements StrategyInterface, DatabaseStrategyInterface
{
    /**
     *
     * @var DataConverter
     */
    private $dataConverter;

    public function __construct()
    {
        $this->dataConverter = new DataConverter();
    }

    /*
     * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::extract()
     */
    public function extract($value)
    {
        return $this->dataConverter->convertBooleanToDb($value);
    }

    /*
     * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::hydrate()
     */
    public function hydrate($value)
    {
        return $this->dataConverter->convertBooleanToLocal($value);
    }

    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        if ($column->getDataType() == "enum" && in_array($column->getErrata("permitted_values"), array(array("true", "false"), array("false", "true")))) {
            return $this;
        }
        return null;
    }
}
