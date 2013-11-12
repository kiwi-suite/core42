<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Core42\Db\TableGateway\Service\TableGatewayAbstractServiceFactory',
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'factories' => array(
            'Zend\Session\Service\SessionManagerFactory' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\Storage\StorageInterface' => 'Zend\Session\Service\StorageFactory',
            'Core42\Authentication\AuthenticationService' => 'Core42\Authentication\Service\AuthenticationFactory',
        ),
        'invokables' => array(
            'MobileDetect' => '\Mobile_Detect',
            'Core42\Hydrator\Strategy\Database\PluginManager' => 'Core42\Hydrator\Strategy\Database\PluginManager'
        ),
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'Core42\Authentication\AuthenticationService',
        ),
    ),

    'service_manager_static_aware' => array(
        'AbstractCommand' => 'Core42\Command\AbstractCommand',
        'SqlQuery' => 'Core42\Db\SqlQuery\SqlQuery',
        'DataConverter' => 'Core42\Db\DataConverter\DataConverter',
    ),

    'view_helpers' => array(
        'invokables' => array(
            'params' => __NAMESPACE__.'\View\Helper\Params',
            'mobileDetect' => __NAMESPACE__ . '\View\Helper\MobileDetect',
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
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__.'\Controller\Cli\Migration' => __NAMESPACE__.'\Controller\Cli\MigrationController',
        ),
    ),

    'tablegateway' => array(

    ),

    'database_hydrator_plugins' => array(
        'boolean'   => 'Core42\Hydrator\Strategy\Database\BooleanStrategy',
        'datetime'  => 'Core42\Hydrator\Strategy\Database\DatetimeStrategy',
        'integer'   => 'Core42\Hydrator\Strategy\Database\IntegerStrategy',
        'float'     => 'Core42\Hydrator\Strategy\Database\FloatStrategy',
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
        'default_name' => '',
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
//        'adapter' => array(
//            'name' => 'Core42\Authentication\Adapter\DbTable\BCryptCheckAdapter',
//            'options' => array(
//                'table_gateway' => '',
//                'identity_column' => '',
//                'credential_column' => '',
//              ),
//        ),
//    ),
);
