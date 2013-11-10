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

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @internal param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        return $this->dataConverter->convertBooleanToDb($value);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @internal param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        return $this->dataConverter->convertBooleanToLocal($value);
    }

    /**
     * @param  \Zend\Db\Metadata\Object\ColumnObject $column
     * @return mixed
     */
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        if ($column->getDataType() == "enum" && in_array($column->getErrata("permitted_values"), array(array("true", "false"), array("false", "true")))) {
            return $this;
        }

        return null;
    }
}
