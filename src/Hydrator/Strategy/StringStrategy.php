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


namespace Core42\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;

class StringStrategy implements StrategyInterface
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
        return $this->castToString($value);
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
        return $this->castToString($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function castToString($value)
    {
        if (!\is_scalar($value)) {
            $value = '';
        }

        return (string) $value;
    }
}
