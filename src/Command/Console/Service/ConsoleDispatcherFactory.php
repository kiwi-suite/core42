<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Console\Service;

use Core42\Command\Console\ConsoleDispatcher;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConsoleDispatcherFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ConsoleDispatcher
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dispatcher = new ConsoleDispatcher();
        $dispatcher->setServiceManager($container);

        return $dispatcher;
    }
}
