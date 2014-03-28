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

            'Core42\Permission\Config' => 'Core42\Permissions\Rbac\Service\RbacConfigFactory',
            'Core42\Permission' => 'Core42\Permissions\Rbac\Service\RbacFactory',

            'Core42\Mail\Transport' => 'Core42\Mail\Transport\Service\TransportFactory',

            'Core42\CommandPluginManager' => 'Core42\Command\Service\CommandPluginManagerFactory',
            'Core42\TableGatewayPluginManager' => 'Core42\Db\TableGateway\Service\TableGatewayPluginManagerFactory',
        ),
        'invokables' => array(
            'MobileDetect' => '\Mobile_Detect',

            'Core42\Hydrator\Strategy\Database\MySQL\PluginManager' => 'Core42\Hydrator\Strategy\Database\MySQL\PluginManager',

            'Core42\Permission\Provider\Role\Array' => 'Core42\Permissions\Rbac\Provider\Role\ArrayProvider',
        ),
        'aliases' => array(
            'AuthenticationService' => 'Core42\Authentication',
            'Zend\Authentication\AuthenticationService' => 'Core42\Authentication',

            'Permission' => 'Core42\Permission',

            'Command' => 'Core42\CommandPluginManager',
            'TableGateway' => 'Core42\TableGatewayPluginManager',
        ),
    ),
);
