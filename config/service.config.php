<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Core42\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Session\Service\SessionManagerFactory'                => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface'                       => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\Storage\StorageInterface'                     => 'Zend\Session\Service\StorageFactory',

            'Core42\Authentication'                                     => 'Core42\Authentication\Service\AuthenticationFactory',
            'Core42\AuthenticationConfig'                               => 'Core42\Authentication\Service\AuthenticationConfigFactory',

            'Core42\Permission\Config'                                  => 'Core42\Permissions\Rbac\Service\RbacConfigFactory',
            'Core42\Permission'                                         => 'Core42\Permissions\Rbac\Service\RbacFactory',

            'Core42\Mail\Transport'                                     => 'Core42\Mail\Transport\Service\TransportFactory',

            'Core42\CommandPluginManager'                               => 'Core42\Command\Service\CommandPluginManagerFactory',
            'Core42\TableGatewayPluginManager'                          => 'Core42\Db\TableGateway\Service\TableGatewayPluginManagerFactory',
            'Core42\SelectQueryPluginManager'                           => 'Core42\Db\SelectQuery\Service\SelectQueryPluginManagerFactory',

            'Metadata'                                                  => 'Core42\Db\Metadata\Service\MetadataServiceFactory',

            'TreeRouteMatcher'                                          => 'Core42\Mvc\TreeRouteMatcher\Service\TreeRouteMatcherFactory',
        ),
        'invokables' => array(
            'MobileDetect'                                              => '\Mobile_Detect',

            'Core42\Hydrator\Strategy\Database\MySQL\PluginManager'     => 'Core42\Hydrator\Strategy\Database\MySQL\PluginManager',

            'Core42\Permission\Provider\Role\Array'                     => 'Core42\Permissions\Rbac\Provider\Role\ArrayProvider',

            'Core42\LoggingProfiler'                                    => 'Core42\Db\Adapter\Profiler\LoggingProfiler',

            'Core42\ConsoleDispatcher'                                  => 'Core42\Command\Console\ConsoleDispatcher',

            'Zend\Authentication\Storage\NonPersistent'                 => 'Zend\Authentication\Storage\NonPersistent',
        ),
        'aliases' => array(
            'AuthenticationService'                                     => 'Core42\Authentication',
            'Zend\Authentication\AuthenticationService'                 => 'Core42\Authentication',

            'Permission'                                                => 'Core42\Permission',

            'Command'                                                   => 'Core42\CommandPluginManager',
            'TableGateway'                                              => 'Core42\TableGatewayPluginManager',
            'SelectQuery'                                               => 'Core42\SelectQueryPluginManager',
        ),
    ),

    'table_gateway' => array(
        'factories' => array(
            'Core42\Migration' => 'Core42\TableGateway\Service\MigrationTableGatewayFactory'
        ),
    ),
);
