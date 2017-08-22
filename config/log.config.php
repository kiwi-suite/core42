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

use Core42\Log\Service\Handler\BrowserConsoleHandlerFactory;
use Core42\Log\Service\Handler\BufferHandlerFactory;
use Core42\Log\Service\Handler\ChromePHPHandlerFactory;
use Core42\Log\Service\Handler\DeduplicationHandlerFactory;
use Core42\Log\Service\Handler\ErrorLogHandlerFactory;
use Core42\Log\Service\Handler\FilterHandlerFactory;
use Core42\Log\Service\Handler\FingersCrossedHandlerFactory;
use Core42\Log\Service\Handler\FirePHPHandlerFactory;
use Core42\Log\Service\Handler\GroupHandlerFactory;
use Core42\Log\Service\Handler\NullHandlerFactory;
use Core42\Log\Service\Handler\PHPConsoleHandlerFactory;
use Core42\Log\Service\Handler\RotatingFileHandlerFactory;
use Core42\Log\Service\Handler\SlackHandlerFactory;
use Core42\Log\Service\Handler\StreamHandlerFactory;
use Core42\Log\Service\Handler\SwiftMailerHandlerFactory;
use Core42\Log\Service\Handler\SyslogHandlerFactory;
use Core42\Log\Service\Handler\SyslogUdpHandlerFactory;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

return [
    'log' => [
        'handler_definitions' => [
            'core' => [
                'handler_type' => StreamHandler::class,
                'config' => [
                    'stream' => 'data/log/core.log',
                    'level' => Logger::ERROR,
                ],
            ],
            'error' => [
                'handler_type' => StreamHandler::class,
                'config' => [
                    'stream' => 'data/log/error.log',
                    'level' => Logger::ERROR,
                ],
            ],
            'filter' => [
                'handler_type' => FilterHandler::class,
                'config' => [
                    'handler' => 'core',
                    'min_level' => Logger::INFO,
                    'max_level' => Logger::ERROR,
                ],
            ],
        ],
        'processor_definitions' => [],

        'logger' => [
            'error' => [
                'handlers' => ['error' => Logger::DEBUG],
                'processors' => [],
            ],
            'core' => [
                'handlers' => ['core' => Logger::DEBUG],
                'processors' => [],
            ],
        ],

        'handler_manager' => [
            'factories' => [
                BrowserConsoleHandler::class                    => BrowserConsoleHandlerFactory::class,
                BufferHandler::class                            => BufferHandlerFactory::class,
                ChromePHPHandler::class                         => ChromePHPHandlerFactory::class,
                DeduplicationHandler::class                     => DeduplicationHandlerFactory::class,
                ErrorLogHandler::class                          => ErrorLogHandlerFactory::class,
                FilterHandler::class                            => FilterHandlerFactory::class,
                FingersCrossedHandler::class                    => FingersCrossedHandlerFactory::class,
                FirePHPHandler::class                           => FirePHPHandlerFactory::class,
                GroupHandler::class                             => GroupHandlerFactory::class,
                NullHandler::class                              => NullHandlerFactory::class,
                PHPConsoleHandler::class                        => PHPConsoleHandlerFactory::class,
                //ProcessHandler::class                           => ProcessHandlerFactory::class,
                RotatingFileHandler::class                      => RotatingFileHandlerFactory::class,
                SlackHandler::class                             => SlackHandlerFactory::class,
                StreamHandler::class                            => StreamHandlerFactory::class,
                SwiftMailerHandler::class                       => SwiftMailerHandlerFactory::class,
                SyslogHandler::class                            => SyslogHandlerFactory::class,
                SyslogUdpHandler::class                         => SyslogUdpHandlerFactory::class,
            ],
            'aliases' => [
            ],
        ],
        'processor_manager' => [
            'factories' => [

            ],
            'aliases' => [
            ],
        ],
    ],
];
