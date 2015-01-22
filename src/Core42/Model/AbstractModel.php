<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Model;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @var array
     */
    protected $properties = array();

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $memento = array();

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = array_fill_keys($this->properties, null);
        if (!empty($data)) {
            $this->populate($data);
        }
        $this->memento();
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
            return (count($this->diff()) > 0);
        }

        if (!in_array($property, $this->properties)) {
            throw new \Exception(sprintf("'%s' not set in property array", $property));
        }

        return !($this->memento[$property] === $this->data[$property]);
    }

    /**
     * @return array
     */
    public function diff()
    {
        return array_udiff_assoc($this->data, $this->memento, function ($value1, $value2) {
            return ($value1 === $value2) ? 0 : 1;
        });
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
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
     * @return mixed
     * @throws \Exception
     */
    protected function get($name)
    {
        if (!in_array($name, $this->properties)) {
            throw new \Exception(sprintf("'%s' not set in property array", $name));
        }

        return $this->data[$name];
    }

    /**
     * @param  string $name
     * @param  mixed $value
     * @param bool $strict
     * @return $this
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
        $this->data[$name] =  $value;

        return $this;
    }

    /**
     * @param $method
     * @param $params
     * @return BaseModel|mixed|null
     * @throws \Exception
     */
    public function __call($method, $params)
    {
        $return = null;

        $variableName = lcfirst(substr($method, 3));
        if (strncasecmp($method, "get", 3) === 0) {
            return $this->get($variableName);
        } elseif (strncasecmp($method, "set", 3) === 0) {
            return $this->set($variableName, $params[0], true);
        }

        throw new \Exception("Method {$method} not found");
    }
}
