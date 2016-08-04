<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\FirePHPHandler;
use Monolog\Logger;
use \Zend\ServiceManager\Factory\FactoryInterface;

class FirePHPFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FirePHPHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new FirePHPHandler($level, $bubble);
    }
}
