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

use Zend\Stdlib\ArraySerializableInterface;

interface ModelInterface extends \Serializable, ArraySerializableInterface, \JsonSerializable
{
    /**
     * @return void
     */
    public function memento();

    /**
     * @param null|string $property
     * @return true
     */
    public function hasChanged($property = null);

    /**
     * @return array
     */
    public function diff();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name);

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value);

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name);
}
