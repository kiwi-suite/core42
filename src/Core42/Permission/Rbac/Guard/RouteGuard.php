<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Guard;

use Zend\Mvc\MvcEvent;

class RouteGuard extends AbstractGuard
{
    /**
     * @param MvcEvent $event
     * @return bool
     */
    public function isGranted(MvcEvent $event)
    {
        $routeName = $event->getRouteMatch()->getMatchedRouteName();

        if (isset($this->options['protected_route'])) {
            if (strpos($routeName, $this->options['protected_route']) !== 0) {
                return true;
            }
        }

        $this->loadAuthorizationService();

        return $this->authorizationService->isGranted(
            'RouteAssertion',
            $routeName
        );
    }
}
