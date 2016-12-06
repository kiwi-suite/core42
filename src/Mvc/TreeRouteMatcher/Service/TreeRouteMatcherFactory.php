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
