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

use Core42\Stdlib\Date;

class DateStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @param array $spec
     * @return bool|Date
     */
    public function hydrate($value, array $spec = [])
    {
        return Date::createFromFormat('Y-m-d', $value);
    }
}
