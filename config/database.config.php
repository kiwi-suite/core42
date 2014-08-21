<?php
namespace Core42;

return array(
    'hydrator_strategy' => array(
        'invokables' => array(
            'Mysql/Integer'        => 'Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy',
            'Mysql/Boolean'        => 'Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy',
            'Mysql/Date'           => 'Core42\Hydrator\Strategy\Database\MySQL\DateStrategy',
            'Mysql/Datetime'       => 'Core42\Hydrator\Strategy\Database\MySQL\DatetimeStrategy',
            'Mysql/Float'          => 'Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy',
        ),
    ),

    'metadata' => array(
        'cache' => 'Cache\Intern',
    ),

    'db' => array(
        'adapters' =>array(
            'Db\Master' => array(
                'driver'    => 'pdo_mysql',
                'database'  => '',
                'username'  => 'root',
                'password'  => '',
                'hostname'  => '127.0.0.1',
                'options'   => array(
                    'buffer_results' => false
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
