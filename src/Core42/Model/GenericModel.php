<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Model;

class GenericModel extends AbstractModel
{
    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
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
        return array_keys($this->data);
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
            $this->properties[] = $name;
        }

        $this->data[$name] =  $value;

        return $this;
    }
}
