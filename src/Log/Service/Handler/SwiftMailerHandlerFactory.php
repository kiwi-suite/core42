<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\NullHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class SwiftMailerHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SwiftMailerHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['mailer'])) {
            throw new ServiceNotCreatedException('mailer option not found for SwiftMailer');
        }
        if (empty($options['message'])) {
            throw new ServiceNotCreatedException('message option not found for SwiftMailer');
        }

        $level = (!empty($options['level'])) ? $options['level'] : Logger::ERROR;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new SwiftMailerHandler($options['mailer'], $options['message'], $level, $bubble);
    }
}
