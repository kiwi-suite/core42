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
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        
        if (!isset($config['log'][$requestedName])) {
            throw new ServiceNotFoundException('unable to get config for "' . $requestedName . '"');
        }

        $handlers = [];
        foreach ($config['log'][$requestedName]['handlers'] as $handler) {
            if (empty($config['log']['handler_definitions'][$handler])) {
                throw new ServiceNotCreatedException('unable to find handler definition for "' . $handler . '"');
            }

            $handlerConfig = [];
            if (!empty($config['log']['handler_definitions'][$handler]['config'])) {
                $handlerConfig = $config['log']['handler_definitions'][$handler]['config'];
            }
            
            $handlers[] = $container->build($config['log']['handler_definitions'][$handler]['handler_type'], $handlerConfig);
        }

        $processors = [];
        foreach ($config['log'][$requestedName]['processors'] as $processor) {

        }

        $logger = new Logger($requestedName, $handlers, $processors);

        return $logger;
    }
    
    protected function getHandler(ServiceLocatorInterface $container, $handler, $config)
    {
        switch ($handler) {
            case 'null':
                
                break;
            
        }
    }
}
