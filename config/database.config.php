<?php
namespace Core42;

use Core42\Hydrator\Strategy\Database\MySQL\BooleanStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\DateStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\DateTimeStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\FloatStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\IntegerStrategy;
use Core42\Hydrator\Strategy\Database\MySQL\StringStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'hydrator_strategy' => [
        'mysql' => [
            'factories' => [
                IntegerStrategy::class        => InvokableFactory::class,
                BooleanStrategy::class        => InvokableFactory::class,
                DateStrategy::class           => InvokableFactory::class,
                DateTimeStrategy::class       => InvokableFactory::class,
                FloatStrategy::class          => InvokableFactory::class,
                StringStrategy::class         => InvokableFactory::class,
            ],
            'aliases' => [
                'Integer'        => IntegerStrategy::class,
                'Boolean'        => BooleanStrategy::class,
                'Date'           => DateStrategy::class,
                'DateTime'       => DateTimeStrategy::class,
                'Float'          => FloatStrategy::class,
                'String'         => StringStrategy::class,
            ],
        ]
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
