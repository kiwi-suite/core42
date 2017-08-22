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

use Zend\Hydrator\Strategy\StrategyInterface;

class ArrayStrategy implements StrategyInterface
{
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value the original value
     * @param object $object (optional) The original object for context
     * @return mixed returns the value that should be extracted
     */
    public function extract($value)
    {
        return $this->castToArray($value);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value the original value
     * @param array $data (optional) The original data for context
     * @return mixed returns the value that should be hydrated
     */
    public function hydrate($value)
    {
        return $this->castToArray($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function castToArray($value)
    {
        if (!\is_array($value)) {
            $value = [];
        }

        return (array) $value;
    }
}
