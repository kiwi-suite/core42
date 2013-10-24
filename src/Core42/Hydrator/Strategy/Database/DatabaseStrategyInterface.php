<?php
namespace Core42\Hydrator\Strategy\Database;

interface DatabaseStrategyInterface
{
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column);
}
