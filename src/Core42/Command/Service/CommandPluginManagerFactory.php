<?php
namespace Core42\Command\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class CommandPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'Core42\Command\Service\CommandPluginManager';
}
