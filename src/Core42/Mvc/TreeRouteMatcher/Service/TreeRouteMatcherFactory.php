<?php
namespace Core42\Mvc\TreeRouteMatcher\Service;

use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TreeRouteMatcherFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return TreeRouteMatcher
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $routeMatch = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();
        $router = $serviceLocator->get('Router');

        return new TreeRouteMatcher($routeMatch, $router);
    }
}
