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
