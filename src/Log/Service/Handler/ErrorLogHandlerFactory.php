<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

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

        $messageType = (!empty($options['message_type'])) ? $options['message_type'] : ErrorLogHandler::OPERATING_SYSTEM;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $expandNewlines = (!empty($options['expand_newlines'])) ? $options['expand_newlines'] : false;

        return new ErrorLogHandler($messageType, $level, $bubble, $expandNewlines);
    }
}
