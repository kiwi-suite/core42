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
use Monolog\Handler\FingersCrossedHandler;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use \Zend\ServiceManager\Factory\FactoryInterface;

class FingersCrossedHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FingersCrossedHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($options['handler'])) {
            throw new ServiceNotCreatedException('handler option not found for FingersCrossedHandler');
        }

        /* @var HandlerPluginManager $handlerPluginManager */
        $handlerPluginManager = $container->get(HandlerPluginManager::class);
        $handler = $handlerPluginManager->get($options['handler']);

        $activationStrategy = (!empty($options['activation_strategy'])) ? $options['activation_strategy'] : null;
        $bufferSize = (!empty($options['buffer_size'])) ? $options['buffer_size'] : 0;
        $bubble = (!empty($options['bubble'])) ? $options['bubble'] : true;
        $stopBuffering = (!empty($options['stop_buffering'])) ? $options['stop_buffering'] : true;
        $passthruLevel = (!empty($options['passthru_level'])) ? $options['passthru_level'] : null;

        return new FingersCrossedHandler(
            $handler,
            $activationStrategy,
            $bufferSize,
            $bubble,
            $stopBuffering,
            $passthruLevel
        );
    }
}
