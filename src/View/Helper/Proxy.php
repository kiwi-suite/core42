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


namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Proxy extends AbstractHelper
{
    /**
     * @var mixed
     */
    protected $object;

    /**
     * Proxy constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @param $method
     * @param $attributes
     * @return mixed
     */
    public function __call($method, $attributes)
    {
        if (empty($this->object)) {
            return null;
        }

        return \call_user_func_array([$this->object, $method], $attributes);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (empty($this->object)) {
            return null;
        }

        return $this->object->{$name};
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (empty($this->object)) {
            return;
        }

        $this->object->{$name} = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if (empty($this->object)) {
            return false;
        }

        return isset($this->object->{$name});
    }
}
