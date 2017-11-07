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

namespace Core42\Log\Service\Handler;

use Core42\Log\Service\HandlerPluginManager;
use Interop\Container\ContainerInterface;
use Monolog\Handler\BufferHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class BufferHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return BufferHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['handler'])) {
            throw new ServiceNotCreatedException('handler option not found for BufferHandler');
        }

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);
        $handler = $handlerPluginManager->get($options['handler']);

        $bufferLimit = (!empty($options['buffer_limit'])) ? $options['buffer_limit'] : 0;
        $level = (!empty($options['level'])) ? $options['level'] : Logger::DEBUG;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $flushOnOverflow = (!empty($options['flush_on_overflow'])) ? $options['flush_on_overflow'] : false;

        return new BufferHandler($handler, $bufferLimit, $level, $bubble, $flushOnOverflow);
    }
}
