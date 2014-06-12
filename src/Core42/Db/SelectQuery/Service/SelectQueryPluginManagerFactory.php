<?php
namespace Core42\Db\SelectQuery\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class SelectQueryPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'Core42\Db\SelectQuery\Service\SelectQueryPluginManager';
}
