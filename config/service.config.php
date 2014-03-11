<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Session\Service\SessionManagerFactory' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\Storage\StorageInterface' => 'Zend\Session\Service\StorageFactory',

            'Core42\Authentication' => 'Core42\Authentication\Service\AuthenticationFactory',
            'Core42\AuthenticationConfig' => 'Core42\Authentication\Service\AuthenticationConfigFactory',

            'Core42\Acl' => 'Core42\Permissions\Acl\Service\AclFactory',
            'Core42\AclConfig' => 'Core42\Permissions\Acl\Service\AclConfigFactory',

            'Core42\CommandPluginManager' => 'Core42\Command\Service\CommandPluginManagerFactory',
            'Core42\TableGatewayPluginManager' => 'Core42\Db\TableGateway\Service\TableGatewayPluginManagerFactory',
        ),
        'invokables' => array(
            'MobileDetect' => '\Mobile_Detect',

            'Core42\Hydrator\Strategy\Database\MySQL\PluginManager' => 'Core42\Hydrator\Strategy\Database\MySQL\PluginManager',

            'Core42\Permissions\Acl\Guard\Route' => 'Core42\Permissions\Acl\Guard\Route',
            'Core42\Permissions\Acl\Provider\ArrayProvider' => 'Core42\Permissions\Acl\Provider\ArrayProvider',
        ),
        'aliases' => array(
            'Acl' => 'Core42\Acl',
            'AuthenticationService' => 'Core42\Authentication',
            'Zend\Authentication\AuthenticationService' => 'Core42\Authentication',

            'Command' => 'Core42\CommandPluginManager',
            'TableGateway' => 'Core42\TableGatewayPluginManager',
        ),
    ),
);
