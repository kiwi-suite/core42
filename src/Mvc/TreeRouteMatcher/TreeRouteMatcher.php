<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Mvc\TreeRouteMatcher;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;

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
