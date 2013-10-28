<?php
namespace Core42\Hydrator\Strategy\Database;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class IntegerStrategy implements StrategyInterface, DatabaseStrategyInterface
{
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        return (in_array($column->getDataType(), array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'))) ? $this : null;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        return (int)$value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        return (int) $value;
    }
}
