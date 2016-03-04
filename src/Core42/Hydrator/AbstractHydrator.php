<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator;

use Zend\Hydrator\ArraySerializable;

class AbstractHydrator extends ArraySerializable
{
    /**
     *
     * @param boolean $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = false)
    {
        if ($underscoreSeparatedKeys === true) {
            //TODO CamelCase
        }
        parent::__construct($underscoreSeparatedKeys);
    }


    /**
     * @param array $data
     * @return array
     */
    public function extractArray(array $data)
    {
        foreach ($data as $name => $value) {
            $data[$name] = $this->extractValue($name, $value);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function hydrateArray(array $data)
    {
        foreach ($data as $name => $value) {
            $data[$name] = $this->hydrateValue($name, $value);
        }
        return $data;
    }
}
