<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Core42\Db\TableGateway\Service\TableGatewayAbstractServiceFactory',
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            'Core42\Authentication\Service\PluginAbstractFactory',
        ),
        'factories' => array(
            'Zend\Session\Service\SessionManagerFactory' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\Storage\StorageInterface' => 'Zend\Session\Service\StorageFactory',
            'Core42\Authentication\AuthenticationService' => 'Core42\Authentication\Service\AuthenticationFactory',
        ),
        'invokables' => array(
            'MobileDetect' => '\Mobile_Detect',
            'Core42\Hydrator\Strategy\Database\MySQL\PluginManager' => 'Core42\Hydrator\Strategy\Database\MySQL\PluginManager'
        ),
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'Core42\Authentication\AuthenticationService',
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
            'inputManager' => __NAMESPACE__ . '\View\Helper\InputManager',
        ),
    ),

    'controller_plugins' => array(
        'factories' => array(
            'mobileDetect' => 'Core42\Mvc\Controller\Plugin\Service\MobileDetectFactory',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'migration-make' => array(
                    'options' => array(
                        'route'    => 'migration-make',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'make'
                        ),
                    ),
                ),
                'migration-migrate' => array(
                    'options' => array(
                        'route'    => 'migration-migrate',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'migrate'
                        ),
                    ),
                ),
                'migration-rollback' => array(
                    'options' => array(
                        'route'    => 'migration-rollback',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'rollback'
                        ),
                    ),
                ),
                'migration-reset' => array(
                    'options' => array(
                        'route'    => 'migration-reset',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'reset'
                        ),
                    ),
                ),

                'seeding-make' => array(
                    'options' => array(
                        'route'    => 'seeding-make <name>',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'make'
                        ),
                    ),
                ),
                'seeding-seed' => array(
                    'options' => array(
                        'route'    => 'seeding-seed [<name>]',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'seed'
                        ),
                    ),
                ),
                'seeding-reset' => array(
                    'options' => array(
                        'route'    => 'seeding-reset [<name>]',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'reset'
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__.'\Controller\Cli\Migration' => __NAMESPACE__.'\Controller\Cli\MigrationController',
            __NAMESPACE__.'\Controller\Cli\Seeding' => __NAMESPACE__.'\Controller\Cli\SeedingController',
        ),
    ),

    'tablegateway' => array(

    ),

    'database_hydrator_plugins' => array(
        'mysql' => array(
            'boolean'   => 'Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy',
            'datetime'  => 'Core42\Hydrator\Strategy\Database\MySQL\DatetimeStrategy',
            'integer'   => 'Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy',
            'float'     => 'Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy',
        ),
    ),

    'caches' => array(
        'Cache\Intern' => array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => 'data/cache/',
                    'namespace' => 'cache_intern'
                ),
            ),
            'plugins' => array(
                'Serializer'
            ),
        ),
        'Cache\InternStatic' => array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => 'data/cache/',
                    'namespace' => 'cache_internstatic'
                ),
            ),
            'plugins' => array(
                'Serializer'
            ),
        ),
    ),

    'migration' => array(
        'migration_dir' => 'data/database/migrations/',
    ),

    'seeding' => array(
        'seeding_dir' => 'data/database/seeding/',
    ),

    'db' => array(
        'adapters' =>array(
            'Db\Master' => array(
                'driver'    => 'mysqli',
                'database'  => '',
                'username'  => 'root',
                'password'  => '',
                'hostname'  => '127.0.0.1',
                'options'   => array(
                    'buffer_results' => true
                ),
                'charset'   => 'utf8',
            ),
// Set this for using Master/Slave Adapters
//             'Db\Slave' => array(
//                 'driver'    => 'mysqli',
//                 'database'  => '',
//                 'username'  => 'root',
//                 'password'  => '',
//                 'hostname'  => '127.0.0.1',
//                 'options'   => array(
//                     'buffer_results' => true
//                 ),
//                 'charset'   => 'utf8',
//             ),
        ),
    ),

    'log' => array(
        'Log\Dev' => array(
           'writers' => array(
               array(
                      'name' => 'chromephp'
               ),
               array(
                      'name' => 'firephp'
               ),
            ),
        ),
    ),

    'session_config' => array(
        'name' => 'sid',
        'use_trans_sid' => false,
        'use_cookies' => true,
        'use_only_cookies' => true,
    ),

    'session_storage' => array(
        'type' => 'Zend\Session\Storage\SessionArrayStorage',
    ),

    'session_manager' => array(
        'enable_trans_sid_check' => false,
        'validator' => array(
        ),
    ),

//    'authentication' => array(
//        'plugins' => array(
//            'Authentication\Plugin\TableGateway' => array(
//                'name' => 'Core42\Authentication\Plugin\TableGateway',
//                'options' => array(
//                    'table_gateway' => 'Ecrm\UserTableGateway',
//                    'identity_column' => 'username',
//                    'credential_column' => 'password',
//                ),
//            ),
//        ),
//        'adapter' => 'Authentication\Plugin\TableGateway',
//        'storage' => 'Authentication\Plugin\TableGateway',
//    ),
);
