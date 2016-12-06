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

namespace Core42\Model;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $memento = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->populate($data);
            $this->memento();
        }
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return void
     */
    public function memento()
    {
        $this->memento = $this->data;
    }

    /**
     * @param null|string $property
     * @return true
     * @throws \Exception
     */
    public function hasChanged($property = null)
    {
        if ($property === null) {
            return count($this->diff()) > 0;
        }

        if (!in_array($property, $this->properties)) {
            throw new \Exception(sprintf("'%s' not set in property array", $property));
        }

        if (!array_key_exists($property, $this->data) && !array_key_exists($property, $this->memento)) {
            return false;
        }

        if (array_key_exists($property, $this->data) && !array_key_exists($property, $this->memento)) {
            return true;
        }

        if (!array_key_exists($property, $this->data) && array_key_exists($property, $this->memento)) {
            return true;
        }

        return !($this->memento[$property] === $this->data[$property]);
    }

    /**
     * @return array
     */
    public function diff()
    {
        $changes = [];

        foreach ($this->properties as $property) {
            if ($this->hasChanged($property)) {
                $changes[$property] = $this->get($property);
            }
        }

        return $changes;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->recursiveToArray($this->data);
    }

    /**
     * @param array $array
     * @return array
     */
    protected function recursiveToArray(array $array)
    {
        $result = [];
        foreach ($array as $name => $value) {
            if ($value instanceof ModelInterface) {
                $value = $value->toArray();
            }

            if (is_array($value)) {
                $value = $this->recursiveToArray($value);
            }
            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->toArray();
    }

    /**
     * @param array $data
     * @return void
     */
    public function exchangeArray(array $data)
    {
        $this->populate($data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data)
    {
        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * @param  string $name
     * @param mixed $default
     * @return mixed
     * @throws \Exception
     */
    protected function get($name, $default = null)
    {
        if (!in_array($name, $this->properties)) {
            throw new \Exception(sprintf("'%s' not set in property array", $name));
        }

        if (!array_key_exists($name, $this->data)) {
            return $default;
        }

        return $this->data[$name];
    }

    /**
     * @param  string $name
     * @param  mixed $value
     * @param bool $strict
     * @return ModelInterface
     * @throws \Exception
     */
    protected function set($name, $value, $strict = false)
    {
        if (!in_array($name, $this->properties)) {
            if ($strict === true) {
                throw new \Exception(sprintf("'%s' not set in property array", $name));
            }

            return $this;
        }
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * @param $method
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $params)
    {
        $return = null;

        $variableName = lcfirst(substr($method, 3));
        if (strncasecmp($method, 'get', 3) === 0) {
            return $this->get($variableName);
        } elseif (strncasecmp($method, 'set', 3) === 0) {
            return $this->set($variableName, $params[0], true);
        }

        throw new \Exception("Method {$method} not found");
    }

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        return $this->get($name, true);
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value, true);
    }

    /**
     * @inheritDoc
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'data' => $this->data,
            'properties' => $this->properties,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $unserialized = unserialize($serialized);

        $this->properties = $unserialized['properties'];
        $this->populate($unserialized['data']);
        $this->memento();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'data' => $this->data,
            'properties' => $this->properties,
        ];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value, true);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->properties);
    }
}
