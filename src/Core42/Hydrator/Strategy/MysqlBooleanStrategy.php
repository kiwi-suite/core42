<?php
namespace Core42\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class MysqlBooleanStrategy implements StrategyInterface
{
	/*
	 * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::extract()
	 */
	public function extract($value)
	{
		return ($value === true) ? 1 : 0;
	}

	/*
	 * @see \Zend\Stdlib\Hydrator\Strategy\StrategyInterface::hydrate()
	 */
	public function hydrate($value)
	{
        return (boolean) $value;
	}

}
