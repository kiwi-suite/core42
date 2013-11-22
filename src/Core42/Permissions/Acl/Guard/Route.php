<?php
namespace Core42\Permissions\Acl\Guard;


use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class Route extends AbstractListenerAggregate
{

    /**
     * @var array
     */
    private $options = array();

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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }

    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

    public function onRoute(MvcEvent $event)
    {
        /** @var $authenticationService \Core42\Permissions\Acl\Acl */
        $authenticationService = $event->getApplication()->getServiceManager()->get('Core42\Acl');
        $routeName = 'route/' . $event->getRouteMatch()->getMatchedRouteName();
        if (!$authenticationService->hasResource($routeName) && isset($this->options['allow_on_no_resource']) && $this->options['allow_on_no_resource'] === true) {
            return;
        }

        if ($authenticationService->isIdentityAllowed($routeName)) {
            return;
        }

        $url = $event->getRouter()->assemble(array(), array('name' => 'admin/login'));
        $response=$event->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        return $response;
    }
}
