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
