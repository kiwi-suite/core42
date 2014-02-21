<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Core42\Db\TableGateway\Service\TableGatewayAbstractServiceFactory',

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
        ),
    ),

    'service_manager_static_aware' => array(
        'AbstractCommand' => 'Core42\Command\AbstractCommand',
        'SqlQuery' => 'Core42\Db\SqlQuery\SqlQuery',
    ),

    'view_helpers' => array(
        'invokables' => array(
            'params' => __NAMESPACE__.'\View\Helper\Params',
            'mobileDetect' => __NAMESPACE__ . '\View\Helper\MobileDetect',
            'valueManager' => __NAMESPACE__ . '\View\Helper\ValueManager',
            'acl' => __NAMESPACE__ . '\View\Helper\Acl',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'partial/value-manager/input' => __DIR__ . '/../view/partial/value-manager/input.phtml',
        ),
    ),

    'value_manager' => array(),

    'controller_plugins' => array(
        'factories' => array(
            'mobileDetect' => 'Core42\Mvc\Controller\Plugin\Service\MobileDetectFactory',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__.'\Controller\Cli\Seeding' => __NAMESPACE__.'\Controller\Cli\SeedingController',
        ),
    ),

    'seeding' => array(
        'seeding_dir' => 'data/database/seeding/',
    ),

//    'authentication' => array(
//        'default' => array(
//            'plugins' => array(
//                'Authentication\Plugin\TableGateway' => array(
//                    'name' => 'Core42\Authentication\Plugin\TableGateway',
//                    'options' => array(
//                        'table_gateway' => 'Core42\UserTableGateway',
//                        'identity_column' => 'username',
//                        'credential_column' => 'password',
//                    ),
//                ),
//            ),
//            'adapter' => 'Authentication\Plugin\TableGateway',
//            'storage' => 'Authentication\Plugin\TableGateway',
//        ),
//        'routes' => array(
//            'route/you/want' => 'default',
//        ),
//    ),
);
