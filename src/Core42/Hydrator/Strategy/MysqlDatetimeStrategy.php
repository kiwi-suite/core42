<?php
namespace Core42\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class MysqlDatetimeStrategy implements StrategyInterface
{
	/*
	 * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::extract()
	 */
	public function extract($value)
	{
		if ($value instanceof \DateTime) {
            return date("Y-m-d H:i:s", $value->getTimestamp());
		}
		return $value;
	}

	/*
	 * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::hydrate()
	 */
	public function hydrate($value)
	{
        return new \DateTime($value);
	}

}
