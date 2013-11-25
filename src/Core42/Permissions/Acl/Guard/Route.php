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
        /** @var $acl \Core42\Permissions\Acl\Acl */
        $acl = $event->getApplication()->getServiceManager()->get('Core42\Acl');
        $routeName = 'route/' . $event->getRouteMatch()->getMatchedRouteName();
        if (!$acl->hasResource($routeName) && isset($this->options['allow_on_no_resource']) && $this->options['allow_on_no_resource'] === true) {
            return;
        }

        do {
            if ($acl->hasResource($routeName) && $acl->isIdentityAllowed($routeName)) {
                return;
            }

            $tmpRoute = str_ireplace("route/", "", str_ireplace("/*", "", $routeName));
            $parts = explode("/", $tmpRoute);
            if (count($parts) <= 1) {
                $routeName = null;
            } else {
                unset($parts[count($parts) - 1]);
                $routeName = 'route/' . implode("/", $parts) . '/*';
            }
        } while ($routeName !== null);

        if ($acl->isIdentityAllowed(null)) {
            return;
        }

        $options = $acl->getRole($acl->getIdentityRole())->getOptions();

        $url = $event->getRouter()->assemble(array(), array('name' => $options['redirect_route']));
        $response=$event->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();

        return $response;
    }
}
