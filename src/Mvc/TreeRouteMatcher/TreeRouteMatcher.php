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

namespace Core42\Mvc\TreeRouteMatcher;

use Zend\Router\RouteMatch;
use Zend\Router\RouteStackInterface;

class TreeRouteMatcher
{
    /**
     * @var RouteMatch|null
     */
    protected $routeMatch;

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @param RouteMatch $routeMatch
     * @param RouteStackInterface $router
     */
    public function __construct(RouteStackInterface $router, RouteMatch $routeMatch = null)
    {
        $this->routeMatch = $routeMatch;

        $this->router = $router;
    }

    /**
     * @param $config
     * @param string $key
     * @return string
     */
    public function getConfigKey($config, $key = 'default')
    {
        if (empty($config) || empty($this->routeMatch)) {
            return $key;
        }

        $currentRouteName = $this->routeMatch->getMatchedRouteName();

        $foundRouteLen = 0;
        foreach ($config as $route => $auth) {
            //route is begin of currentRoute
            if (strpos($currentRouteName, $route) === 0) {
                if (strlen($route) > $foundRouteLen) {
                    $key = $auth;
                    $foundRouteLen = strlen($route);
                }
            }
        }

        return $key;
    }
}
