<?php
namespace Core42;

return [
    'hydrator_strategy' => [
        'invokables' => [
            'Mysql/Integer'        => 'Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy',
            'Mysql/Boolean'        => 'Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy',
            'Mysql/Date'           => 'Core42\Hydrator\Strategy\Database\MySQL\DateStrategy',
            'Mysql/Datetime'       => 'Core42\Hydrator\Strategy\Database\MySQL\DatetimeStrategy',
            'Mysql/Float'          => 'Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy',
        ],
    ],

    'metadata' => [
        'cache' => (DEVELOPMENT_MODE) ? false : 'Cache\Intern',
    ],

    'db' => [
        'adapters' => [
            'Db\Master' => [
                'driver'    => 'pdo_mysql',
                'database'  => '',
                'username'  => 'root',
                'password'  => '',
                'hostname'  => '127.0.0.1',
                'options'   => [
                    'buffer_results' => false
                ],
                'charset'   => 'utf8',
            ],
// Set this for using Master/Slave Adapters
//             'Db\Slave' => [
//                 'driver'    => 'mysqli',
//                 'database'  => '',
//                 'username'  => 'root',
//                 'password'  => '',
//                 'hostname'  => '127.0.0.1',
//                 'options'   => [
//                     'buffer_results' => true
//                 ],
//                 'charset'   => 'utf8',
//             ],
        ],
    ],
];
