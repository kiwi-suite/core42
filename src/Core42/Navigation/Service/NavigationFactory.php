<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Service;

use Core42\Navigation\Navigation;
use Core42\Navigation\Options\NavigationOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var NavigationOptions $options */
        $options = $serviceLocator->get('Core42\NavigationOptions');

        $navigation = new Navigation();
        $navigation->setServiceManager($serviceLocator);

        foreach ($options->getListeners() as $navName => $_listener) {
            foreach ($_listener as $priority => $listener) {
                if (is_string($listener)) {
                    if ($serviceLocator->has($listener)) {
                        $listener = $serviceLocator->get($listener);
                    } else {
                        $listener = new $listener();
                    }
                }

                if (is_numeric($priority)) {
                    $navigation->getEventManager($navName)->attachAggregate($listener, $priority);
                } else {
                    $navigation->getEventManager($navName)->attachAggregate($listener);
                }
            }
        }

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();

        $navigation->setRouteMatch($routeMatch);
        $navigation->setRouter($router);

        return $navigation;
    }
}
