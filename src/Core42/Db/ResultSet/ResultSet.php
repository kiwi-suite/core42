<?php
namespace Core42\Db\ResultSet;

use Zend\Db\ResultSet\HydratingResultSet;

class ResultSet extends HydratingResultSet
{
    public function current()
    {
        $object = parent::current();
        $object->memento();

        return $object;
    }
}
