<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */


namespace Core42\Log\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Monolog\Logger;

class AbstractLoggerFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');

        return isset($config['log']['logger'][$requestedName]);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['log']['logger'][$requestedName];

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);

        $handlers = [];
        foreach ($config['handlers'] as $key => $value) {
            $handler = $value;
            $config = [];
            if (\is_int($value)) {
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
