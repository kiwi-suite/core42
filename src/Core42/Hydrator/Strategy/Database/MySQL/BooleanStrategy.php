<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Database\MySQL;

use Core42\Hydrator\Strategy\Database\DatabaseStrategyInterface;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class BooleanStrategy implements StrategyInterface, DatabaseStrategyInterface
{
    /**
     * @var boolean
     */
    private $isNullable;

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @internal param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if ($this->isNullable && $value === null) {
            return null;
        }

        return ($value === true) ? "true" : "false";
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
        if ($this->isNullable && $value === null) {
            return null;
        }

        return ($value === "true") ? true : false;
    }

    /**
     * @param  \Zend\Db\Metadata\Object\ColumnObject $column
     * @return mixed
     */
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        $check = array(array("true", "false"), array("false", "true"));
        if ($column->getDataType() == "enum" && in_array($column->getErrata("permitted_values"), $check)) {
            $this->isNullable = $column->getIsNullable();

            return $this;
        }

        return null;
    }
}
