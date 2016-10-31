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

use Core42\Stdlib\DateTime;

class DateTimeStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @return bool|DateTime
     */
    public function hydrate($value)
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $value);
    }
}
