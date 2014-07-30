<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\SelectQuery\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class SelectQueryPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'Core42\Db\SelectQuery\Service\SelectQueryPluginManager';
}
