<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Guard;

use Core42\Permission\Rbac\AuthorizationService;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

interface GuardInterface extends ListenerAggregateInterface
{
    const GUARD_UNAUTHORIZED = 'guard-unauthorized';

    /**
     * @param  MvcEvent $event
     * @return bool
     */
    public function isGranted(MvcEvent $event);

    /**
     * @param AuthorizationService $authorizationService
     */
    public function setAuthorizationService(AuthorizationService $authorizationService);
}
