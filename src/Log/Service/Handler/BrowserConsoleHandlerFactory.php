<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Logger;
use \Zend\ServiceManager\Factory\FactoryInterface;

class BrowserConsoleFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return BrowserConsoleHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new BrowserConsoleHandler($level, $bubble);
    }
}
