<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Listener;

use Core42\Navigation\Navigation;
use Core42\Navigation\NavigationEvent;
use Core42\Navigation\Page\Page;
use Core42\Permission\Rbac\AuthorizationService;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

class RbacListener extends AbstractListenerAggregate
{
    /**
     * @var AuthorizationService
     */
    private $authorizationService;

    /**
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Navigation::EVENT_IS_ALLOWED, array($this, 'isAllowed'));
    }

    /**
     * @param NavigationEvent $event
     * @return bool
     */
    public function isAllowed(NavigationEvent $event)
    {
        /** @var Page $page */
        $page = $event->getTarget();

        if ($page->getOption('permission', null) === null) {
            return true;
        }

        return $this->authorizationService->isGranted($page->getOption("permission"));
    }
}
