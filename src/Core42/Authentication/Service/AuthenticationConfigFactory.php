<?php
namespace Core42\Authentication\Service;

use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationConfigFactory implements FactoryInterface
{
    private $configkey = 'authentication';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config[$this->configkey]) || empty($config[$this->configkey])) {
            return array();
        }
        $authName = 'default';
        if (isset($config[$this->configkey]['routes']) && !empty($config[$this->configkey]['routes'])) {
            $routeMatch = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();
            if ($routeMatch instanceof RouteMatch) {
                $currentRoute = $routeMatch->getMatchedRouteName();

                $foundRouteLen = 0;
                foreach ($config[$this->configkey]['routes'] as $route => $auth) {
                    //route is begin of currentRoute
                    if (strpos($currentRoute, $route) === 0) {
                        if (strlen($route) > $foundRouteLen) {
                            $authName = $auth;
                            $foundRouteLen = strlen($route);
                        }
                    }
                }
            }
        }
        return $config[$this->configkey][$authName];
    }

}
