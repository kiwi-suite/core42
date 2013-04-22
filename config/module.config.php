<?php
namespace Core42;

return array(
    'service_manager' => array(
        'factories' => array(
            'db_master' => 'Core42\Db\Adapter\MasterServiceFactory',
            'db_slave' => 'Core42\Db\Adapter\SlaveServiceFactory',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'service_manager_static_aware' => array(
        'AbstractCommand' => 'Core42\Command\AbstractCommand',
        'AbstractTableGateway' => 'Core42\Db\TableGateway\AbstractTableGateway',
    ),

    'db_master' => array(
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
    'db_slave' => false,
);
