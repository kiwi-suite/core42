<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Model;

use Core42\Hydrator\ModelHydrator;

abstract class AbstractModel
{
    /**
     * @var ModelHydrator
     */
    private $hydrator;

    private $modelProperties = array();

    /**
     * @var null|array
     */
    private $memento = null;

    /**
     *
     */
    public function __construct()
    {
        $this->memento();
    }

    /**
     * @param  string $name
     * @return mixed
     */
    protected function get($name)
    {
        if (array_key_exists($name, $this->modelProperties)) {
            return $this->modelProperties[$name];
        }

        return null;
    }

    /**
     * @param  string                      $name
     * @param  mixed                       $value
     * @return \Core42\Model\AbstractModel
     */
    protected function set($name, $value)
    {
        $this->modelProperties[$name] =  $value;

        return $this;
    }

    /**
     * @return ModelHydrator
     */
    public function getHydrator()
    {
        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }

        return $this->hydrator;
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        $this->getHydrator()->hydrate($data, $this);
    }

    /**
     * @return array
     */
    public function extract()
    {
        return $this->getHydrator()->extract($this);
    }

    /**
     * @return \Core42\Model\AbstractModel
     */
    public function memento()
    {
        $this->memento = $this->modelProperties;

        return $this;
    }

    /**
     * @param  null|string $property
     * @return bool
     */
    public function hasChanged($property = null)
    {
        if ($property === null) {
            return (count($this->diff()) > 0);
        } else {
            return array_key_exists($property, $this->modelProperties);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function diff()
    {
        return array_udiff_assoc($this->modelProperties, $this->memento, function ($value1, $value2) {
            return ($value1 === $value2) ? 0 : 1;
        });
    }
}
