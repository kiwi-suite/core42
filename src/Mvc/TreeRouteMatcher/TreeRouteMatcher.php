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
            if (\mb_strpos($currentRouteName, $route) === 0) {
                if (\mb_strlen($route) > $foundRouteLen) {
                    $key = $auth;
                    $foundRouteLen = \mb_strlen($route);
                }
            }
        }

        return $key;
    }
}
