<?php

namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\NullHandler;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class FilterHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NullHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!empty($options['level'])) {
            return new NullHandler($options['level']);    
        } else {
            return new NullHandler();
        }
    }
}
