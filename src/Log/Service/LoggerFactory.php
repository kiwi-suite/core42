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

namespace Core42\Log\Service;

use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LoggerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!isset($config['log']['logger'][$requestedName])) {
            throw new ServiceNotFoundException('unable to get config for "' . $requestedName . '"');
        }
        $config = $config['log']['logger'][$requestedName];

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);

        $handlers = [];
        foreach ($config['handlers'] as $key => $value) {
            $handler = $value;
            $config = [];
            if (is_int($value)) {
                $handler = $key;
                $config['level'] = $value;
            }

            $handlers[] = $handlerPluginManager->get($handler, $config);
        }

        $processors = [];
        if (!empty($config['processors'])) {
            $processorPluginManager = $container->get(HandlerPluginManager::class);

            foreach ($config['processors'] as $processor) {
                $processors[] = $processorPluginManager->get($processor);
            }
        }

        $logger = new Logger($requestedName, $handlers, $processors);

        return $logger;
    }
}
