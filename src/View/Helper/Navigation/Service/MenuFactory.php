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


namespace Core42\View\Helper\Navigation\Service;

use Core42\Navigation\Service\FilterPluginManager;
use Core42\Navigation\Service\NavigationPluginManager;
use Core42\View\Helper\Navigation\Menu;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MenuFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Menu
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Menu(
            $container->get(NavigationPluginManager::class),
            $container->get(FilterPluginManager::class)
        );
    }
}
