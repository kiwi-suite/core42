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
