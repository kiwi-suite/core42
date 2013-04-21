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
    
    'db_master' => array(
        'driver'    => 'mysqli',
        'database'  => 'push',
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
