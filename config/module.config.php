<?php
namespace Core42;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
    ),
    
    'service_manager_static_aware' => array(
        'AbstractCommand' => 'Core42\Command\AbstractCommand',
        'AbstractTableGateway' => 'Core42\Db\TableGateway\AbstractTableGateway',
        'SqlQuery' => 'Core42\Db\SqlQuery\SqlQuery',
        'DataConverter' => 'Core42\Db\DataConverter\DataConverter',
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
);
