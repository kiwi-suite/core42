<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42;

return [
    'db' => [
        'adapters' => [
            'Db\Master' => [
                'driver'    => 'pdo_mysql',
                'database'  => '',
                'username'  => 'root',
                'password'  => '',
                'hostname'  => '127.0.0.1',
                'options'   => [
                    'buffer_results' => false,
                ],
                'charset'   => 'utf8',
            ],
        ],
    ],
];
