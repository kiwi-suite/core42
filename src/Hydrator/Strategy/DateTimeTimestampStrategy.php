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


namespace Core42\Hydrator\Strategy;

use Core42\Stdlib\DateTime;
use Zend\Hydrator\Strategy\StrategyInterface;

class DateTimeTimestampStrategy implements StrategyInterface
{
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value the original value
     * @internal param object $object (optional) The original object for context
     * @return mixed returns the value that should be extracted
     */
    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            return $value->getTimestamp();
        }

        return $value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value the original value
     * @internal param array $data (optional) The original data for context
     * @return mixed returns the value that should be hydrated
     */
    public function hydrate($value)
    {
        return new DateTime('@' . $value);
    }
}
