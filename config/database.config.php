<?php
namespace Core42;

return array(
    'database_hydrator_plugins' => array(
        'mysql' => array(
            'boolean'   => 'Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy',
            'datetime'  => 'Core42\Hydrator\Strategy\Database\MySQL\DatetimeStrategy',
            'date'      => 'Core42\Hydrator\Strategy\Database\MySQL\DateStrategy',
            'integer'   => 'Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy',
            'float'     => 'Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy',
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
);
