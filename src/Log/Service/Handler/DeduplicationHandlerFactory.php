<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2016 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Log\Service\Handler;

use Core42\Log\Service\HandlerPluginManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class DeduplicationHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DeduplicationHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['handler'])) {
            throw new ServiceNotCreatedException('handler option not found for DeduplicationHandler');
        }

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);
        $handler = $handlerPluginManager->get($options['handler']);

        $store = (!empty($options['store'])) ? $options['store'] : null;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::ERROR;
        $time = (!empty($options['time'])) ? $options['time'] : 60;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;

        return new DeduplicationHandler($handler, $store, $level, $time, $bubble);
    }
}
