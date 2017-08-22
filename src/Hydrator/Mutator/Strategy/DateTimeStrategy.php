<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */


namespace Core42\Hydrator\Mutator\Strategy;

use Core42\Stdlib\DateTime;

class DateTimeStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @param array $spec
     * @return bool|DateTime
     */
    public function hydrate($value, array $spec = [])
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $value);
    }
}
