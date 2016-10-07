<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
}
