<?php
namespace Core42\Db\TableGateway\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class TableGatewayPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'Core42\Db\TableGateway\Service\TableGatewayPluginManager';
}
