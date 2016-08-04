<?php

namespace Core42\Log\Service\Handler;

use Core42\Log\Service\HandlerPluginManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\FilterHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class FilterHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FilterHandlerFactory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['handler'])) {
            throw new ServiceNotCreatedException('handler option not found for FilterHandler');
        }

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);
        $handler = $handlerPluginManager->get($options['handler']);

        $minLevel = (!empty($options['min_level'])) ? $options['min_level'] : Logger::DEBUG;
        $maxLevel = (!empty($options['max_level'])) ? $options['max_level'] : Logger::EMERGENCY;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new FilterHandler($handler, $minLevel, $maxLevel, $bubble);
    }
}
