<?php
namespace Core42;

use Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\DateStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\DatetimeStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'hydrator_strategy' => [
        'factories' => [
            IntegerStrategy::class        => InvokableFactory::class,
            BooleanStrategy::class        => InvokableFactory::class,
            DateStrategy::class           => InvokableFactory::class,
            DatetimeStrategy::class       => InvokableFactory::class,
            FloatStrategy::class          => InvokableFactory::class,
        ],
        'aliases' => [
            'Mysql/Integer'        => IntegerStrategy::class,
            'Mysql/Boolean'        => BooleanStrategy::class,
            'Mysql/Date'           => DateStrategy::class,
            'Mysql/Datetime'       => DatetimeStrategy::class,
            'Mysql/Float'          => FloatStrategy::class,
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
