<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Log\Service;

use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractHandlerFactory implements AbstractFactoryInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * AbstractHandlerFactory constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (!empty($this->config['handler_definitions'][$requestedName])) {
            return true;
        }

        return false;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $handlerConfig = [];
        if (!empty($this->config['handler_definitions'][$requestedName]['config'])) {
            $handlerConfig = $this->config['handler_definitions'][$requestedName]['config'];
        }
        if (!empty($options['level'])) {
            $handlerConfig['level'] = $options['level'];
        }

        $handlerPluginManager = $container->get(HandlerPluginManager::class);

        return $handlerPluginManager->build(
            $this->config['handler_definitions'][$requestedName]['handler_type'],
            $handlerConfig
        );
    }
}
