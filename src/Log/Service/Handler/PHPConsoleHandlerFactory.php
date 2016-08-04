<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Logger;
use \Zend\ServiceManager\Factory\FactoryInterface;

class PHPConsoleFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PHPConsoleHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = (!empty($options['options'])) ? $options['options'] : [];
        $connector = (!empty($options['connector'])) ? $options['connector'] : null;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new PHPConsoleHandler($options, $connector, $level, $bubble);
    }
}
