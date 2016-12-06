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

namespace Core42\Hydrator\Mutator\Service;

use Core42\Hydrator\Mutator\Mutator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MutatorFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return StrategyPluginManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Mutator(
            $container->get(StrategyPluginManager::class)
        );
    }
}
