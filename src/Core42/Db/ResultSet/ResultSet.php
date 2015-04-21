<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\ResultSet;

use Core42\Hydrator\ModelHydrator;
use Zend\Db\ResultSet\HydratingResultSet;

class ResultSet extends HydratingResultSet
{
    /**
     * @return object
     */
    public function current()
    {
        $object = parent::current();
        $object->memento();

        return $object;
    }

    /**
     * Cast result set to array of arrays
     *
     * @return array
     */
    public function toArray()
    {
        $hydrator = new ModelHydrator();

        $return = array();
        foreach ($this as $row) {
            $return[] = $hydrator->extract($row);
        }
        return $return;
    }
}
