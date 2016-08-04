<?php
namespace Core42;

use Core42\Log\Service\Handler\ErrorLogHandlerFactory;
use Core42\Log\Service\Handler\RotatingFileHandlerFactory;
use Core42\Log\Service\Handler\StreamHandlerFactory;
use Core42\Log\Service\Handler\SyslogHandlerFactory;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;

return [
    'log' => [
        'handler_definitions' => [
            'stream' => [
                'handler_type' => StreamHandler::class,
                'config' => [
                    'stream' => '/home/bernhard/dev/demos/digital-reporting/foobar.txt',
                    'level' => Logger::ERROR
                ]
            ],
            'filter' => [
                'handler_type' => FilterHandler::class,
                'config' => [
                    'handler' => 'stream',
                    'min_level' => Logger::INFO,
                    'max_level' => Logger::ERROR
                ]
            ],
        ],
        'processor_definitions' => [],

        'logger' => [
            'Log\Core' => [
                'handlers' => ['stream' => Logger::DEBUG, 'foobar'],
                'processors' => [],
            ],
            'Log\Test' => [
                'handlers' => ['stream'],
                'processors' => [],
            ],
        ],
        

        'handler_manager' => [
            'factories' => [
                StreamHandler::class                            => StreamHandlerFactory::class,
                RotatingFileHandler::class                      => RotatingFileHandlerFactory::class,
                SyslogHandler::class                            => SyslogHandlerFactory::class,
                ErrorLogHandler::class                          => ErrorLogHandlerFactory::class,
                //ProcessHandler::class                           => ProcessHandlerFactory::class,
            ],
            'aliases' => [
            ],
        ],
    ],
];
