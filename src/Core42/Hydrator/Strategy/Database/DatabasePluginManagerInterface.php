<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
