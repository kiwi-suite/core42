<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Hydrator\Mutator\Strategy;

class IntegerStrategy implements StrategyInterface
{


    public function hydrate($value)
    {
        return $this->castToInt($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function castToInt($value)
    {
        if (!is_scalar($value)) {
            $value = 0;
        }

        return (int) $value;
    }
}
