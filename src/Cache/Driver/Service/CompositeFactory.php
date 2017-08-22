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
        if ($options === null) {
            $options = [];
        }

        $drivers = [];

        $selectedDrivers = (!empty($options['drivers']) && \is_string($options['drivers'])) ? $options['drivers'] : "";
        $selectedDrivers = \explode(",", $selectedDrivers);
        foreach ($selectedDrivers as $driver) {
            $driver = \trim($driver);
            if (empty($driver)) {
                continue;
            }

            $drivers[] = $container->get(DriverPluginManager::class)->get($driver);
        }

        $options['drivers'] = $drivers;

        return new Composite($options);
    }
}
