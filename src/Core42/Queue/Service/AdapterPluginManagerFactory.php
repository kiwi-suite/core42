<?php
namespace Core42\Queue\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class AdapterPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'Core42\Queue\Service\AdapterPluginManager';
}
