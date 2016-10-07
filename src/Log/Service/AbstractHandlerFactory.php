<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2016 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Log\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Handler\HandlerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
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
     * @inheritdoc
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
