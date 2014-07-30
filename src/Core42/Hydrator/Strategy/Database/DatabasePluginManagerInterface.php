<?php
namespace Core42\Hydrator\Strategy\Database;

interface DatabasePluginManagerInterface
{
    /**
     * @param \Zend\Db\Metadata\Object\ColumnObject $column
     * @return DatabaseStrategyInterface
     */
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column);

    /**
     * @param $name
     * @return DatabaseStrategyInterface
     */
    public function loadStrategy($name);
}
