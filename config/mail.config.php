<?php
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
