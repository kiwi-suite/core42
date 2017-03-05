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


namespace Core42\Cache\Driver\Service;

use Core42\Cache\Service\DriverPluginManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Stash\Driver\Composite;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CompositeFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $drivers = [];

        $selectedDrivers = (!empty($options['drivers']) && \is_string($options['drivers'])) ? $options['drivers'] : [];
        $selectedDrivers = \explode(",", $selectedDrivers);
        foreach ($selectedDrivers as $driver) {
            $drivers[] = $container->get(DriverPluginManager::class)->get(\trim($driver));
        }

        $options['drivers'] = $drivers;

        return new Composite($options);
    }
}
