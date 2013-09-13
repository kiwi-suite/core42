<?php
namespace Core42\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Core42\Db\DataConverter\DataConverter;

class BooleanStrategy implements StrategyInterface
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

}
