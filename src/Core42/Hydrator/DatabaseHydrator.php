<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator;

use Core42\Model\ModelInterface;

class DatabaseHydrator extends AbstractHydrator
{
    /**
     * @param string $name
     * @param mixed $value
     * @param null|ModelInterface $object
     * @return mixed
     */
    public function extractValue($name, $value, $object = null)
    {
        if ($this->hasStrategy($name) && $value !== null) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->extract($value, $object);
        }
        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param null|array $data
     * @return mixed
     */
    public function hydrateValue($name, $value, $data = null)
    {
        if ($this->hasStrategy($name) && $value !== null) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->hydrate($value, $data);
        }
        return $value;
    }
}
