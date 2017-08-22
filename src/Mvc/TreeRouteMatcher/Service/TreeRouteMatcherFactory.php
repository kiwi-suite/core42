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


namespace Core42\Mvc\TreeRouteMatcher\Service;

use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TreeRouteMatcherFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TreeRouteMatcher
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $router = $container->get('Router');

        return new TreeRouteMatcher($router, $routeMatch);
    }
}
