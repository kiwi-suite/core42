<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorLogHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ErrorLogHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $messageType = (!empty($options['message_type']))
            ? $options['message_type']
            : ErrorLogHandler::OPERATING_SYSTEM;

        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $expandNewlines = (!empty($options['expand_newlines'])) ? $options['expand_newlines'] : false;

        return new ErrorLogHandler($messageType, $level, $bubble, $expandNewlines);
    }
}
