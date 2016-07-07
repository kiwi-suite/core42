<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Guard;

use Core42\Permission\Rbac\RbacManager;
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
     * @param string $authorizationServiceName
     */
    public function setAuthorizationServiceName($authorizationServiceName);

    /**
     * @param RbacManager $rbacManager
     */
    public function setRbacManager(RbacManager $rbacManager);

    /**
     * @param array $options
     */
    public function setOptions(array $options);
}
