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

use Core42\Stdlib\Date;

class DateStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @return bool|Date
     */
    public function hydrate($value)
    {
        return Date::createFromFormat('Y-m-d', $value);
    }
}
