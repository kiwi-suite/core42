<?php
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

    public function __construct(RouteMatch $routeMatch = null, RouteStackInterface $router)
    {
        $this->routeMatch = $routeMatch;

        $this->router = $router;
    }

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
