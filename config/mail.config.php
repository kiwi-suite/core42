<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
