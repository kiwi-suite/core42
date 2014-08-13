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
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

abstract class AbstractGuard extends AbstractListenerAggregate implements GuardInterface
{
    const EVENT_PRIORITY = -5;
    const EVENT_NAME = MvcEvent::EVENT_ROUTE;

    /**
     * @var AuthorizationService
     */
    protected $authorizationService;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(static::EVENT_NAME, [$this, 'onResult'], static::EVENT_PRIORITY);
    }

    /**
     * @param AuthorizationService $authorizationService
     */
    public function setAuthorizationService(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
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

        $event->setError(self::GUARD_UNAUTHORIZED);
        $event->setParam('exception', new UnauthorizedException(
            $this->authorizationService->getName(),
            'You are not authorized to access this resource',
            403
        ));

        $event->stopPropagation(true);

        $application  = $event->getApplication();
        $eventManager = $application->getEventManager();

        $eventManager->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
    }
}
