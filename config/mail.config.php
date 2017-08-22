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
    'mail' => [
        'transport' => [
            'type' => 'null',
            'options' => [],
        ],

        /*
        'transport' => [
            'type' => 'smtp',
            'options' => [
                'host'              => '', //optional, default: localhost
                'port'              => 25, //optional, default: 25
                'encryption'        => '', //optional (ssl|tls), default: tls
                'username'          => '', //optional
                'password'          => '', //optional

            ],
        ],
        'transport' => [
            'type' => 'sendmail',
            'options' => [
                'command'           => '', //optional, default: /usr/sbin/sendmail -bs
            ],
        ],
        'transport' => [
            'type' => 'mail',
            'options' => [
                'extra'             => '', //optional, default: -f%s
            ],
        ],

        */
    ],
];
