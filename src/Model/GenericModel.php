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


namespace Core42\Model;

class GenericModel extends AbstractModel
{
    /**
     * @param  string $name
     * @param  mixed $value
     * @param bool $strict
     * @throws \Exception
     * @return ModelInterface
     */
    protected function set($name, $value, $strict = false)
    {
        if (!\in_array($name, $this->properties)) {
            $this->properties[] = $name;
        }

        return parent::set($name, $value, $strict);
    }
}
