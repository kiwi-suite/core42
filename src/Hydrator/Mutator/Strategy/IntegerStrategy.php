<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Hydrator\Mutator\Strategy;

class IntegerStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @param array $spec
     * @return string
     */
    public function hydrate($value, array $spec = [])
    {
        return $this->castToInt($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function castToInt($value)
    {
        if (!\is_scalar($value)) {
            $value = 0;
        }

        return (int) $value;
    }
}
