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
     * @param  array $rules
     * @return void
     */
    public function setRules(array $rules)
    {
        $this->rules = array();

        foreach ($rules as $key => $value) {
            if (is_int($key)) {
                $routeRegex = $value;
                $roles      = array();
            } else {
                $routeRegex = $key;
                $roles      = (array) $value;
            }

            $this->rules[$routeRegex] = $roles;
        }
    }

    /**
     * @param MvcEvent $event
     * @return bool
     */
    public function isGranted(MvcEvent $event)
    {
        return $this->authorizationService->isGranted(
            'RouteAssertion',
            $event->getRouteMatch()->getMatchedRouteName()
        );
    }
}
