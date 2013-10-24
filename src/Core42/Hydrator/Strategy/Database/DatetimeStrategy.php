<?php
namespace Core42\Hydrator\Strategy\Database;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Core42\Db\DataConverter\DataConverter;

class DatetimeStrategy implements StrategyInterface, DatabaseStrategyInterface
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
        if ($value instanceof \DateTime) {
            return $this->dataConverter->convertDatetimeToDb($value);
        }
        return $value;
    }

    /*
     * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::hydrate()
     */
    public function hydrate($value)
    {
        return $this->dataConverter->convertDatetimeToLocal($value);
    }

    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        return (in_array($column->getDataType(), array('datetime', 'date', 'timestamp'))) ? $this : null;
    }
}
