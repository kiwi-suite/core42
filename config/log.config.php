<?php
namespace Core42;

use Monolog\Handler\StreamHandler;

return [
    'log' => [
        'handler_definitions' => [
            'stream' => [
                'handler_type' => StreamHandler::class,
                'config' => [
                    'stream' => '/home/bernhard/dev/demos/digital-reporting/foobar.txt',
                ]
            ],
        ],
        'processor_definitions' => [],

        'Log\Core' => [
            'handlers' => ['stream'],
            'processors' => [],
        ],
    ],
];
