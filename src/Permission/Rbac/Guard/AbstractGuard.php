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
use Core42\Permission\Rbac\Exception\UnauthorizedException;
use Core42\Permission\Rbac\RbacManager;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

abstract class AbstractGuard extends AbstractListenerAggregate implements GuardInterface
{
    const EVENT_PRIORITY = -5;
    const EVENT_NAME = MvcEvent::EVENT_ROUTE;

    /**
     * @var string
     */
    protected $authorizationServiceName;

    /**
     * @var AuthorizationService
     */
    protected $authorizationService;

    /**
     * @var RbacManager
     */
    protected $rbacManager;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events,  $priority = 1)
    {
        $this->listeners[] = $events->attach(static::EVENT_NAME, [$this, 'onResult'], static::EVENT_PRIORITY);
    }

    /**
     * @param string $authorizationServiceNameName
     */
    public function setAuthorizationServiceName($authorizationServiceNameName)
    {
        $this->authorizationServiceName = $authorizationServiceNameName;
    }

    /**
     * @param RbacManager $rbacManager
     */
    public function setRbacManager(RbacManager $rbacManager)
    {
        $this->rbacManager = $rbacManager;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param  MvcEvent $event
     * @return void
     */
    public function onResult(MvcEvent $event)
    {
        if ($this->isGranted($event)) {
            return;
        }

        $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $event->setError(self::GUARD_UNAUTHORIZED);
        $event->setParam('exception', new UnauthorizedException(
            $this->authorizationService->getName(),
            'You are not authorized to access this resource',
            403
        ));

        $event->stopPropagation(true);

        $application  = $event->getApplication();
        $eventManager = $application->getEventManager();

        $eventManager->triggerEvent($event);
    }

    /**
     *
     */
    protected function loadAuthorizationService()
    {
        if ($this->authorizationService === null) {
            $this->authorizationService = $this->rbacManager->getService($this->authorizationServiceName);
        }
    }
}
