<?php
namespace Core42\Hydrator\Strategy\Database;

interface DatabasePluginManagerInterface
{
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column);

    public function loadStrategy($name);
}
