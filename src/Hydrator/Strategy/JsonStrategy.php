<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\Json\Json;

class JsonStrategy implements StrategyInterface
{
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @internal param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        return Json::encode($this->encodeRecursive($value));
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @internal param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        return Json::decode($value, Json::TYPE_ARRAY);
    }

    /**
     * @param $value
     * @return array|string
     */
    protected function encodeRecursive($value)
    {
        if (is_object($value)) {
            $value = $this->encodeObject($value);
        } elseif (is_array($value)) {
            $value = $this->encodeArray($value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return array|string
     */
    protected function encodeObject($value)
    {
        if (method_exists($value, 'toJson')) {
            $value =  $value->toJson();
        }elseif (method_exists($value, 'toArray')) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            return $this->encodeArray($value);
        }

        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }

    /**
     * @param array $value
     * @return array
     */
    protected function encodeArray(array $value)
    {
        foreach ($value as $name => $val) {
            $value[$name] = $this->encodeRecursive($val);
        }

        return $value;
    }
}
