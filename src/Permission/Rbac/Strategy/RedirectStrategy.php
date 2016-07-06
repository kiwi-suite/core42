<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Strategy;

use Core42\Permission\Rbac\Exception\UnauthorizedException;
use Core42\Permission\Rbac\Options\RedirectStrategyOptions;
use Core42\Permission\Rbac\RbacManager;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class RedirectStrategy extends AbstractStrategy
{
    /**
     * @var RbacManager
     */
    protected $rbacManager;

    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(RbacManager $rbacManager)
    {
        $this->rbacManager = $rbacManager;
    }

    /**
     * @private
     * @param  MvcEvent $event
     * @return void
     */
    public function onError(MvcEvent $event)
    {
        $exception = $event->getParam('exception');

        if (!($exception instanceof UnauthorizedException)
            || ($result = $event->getResult() instanceof HttpResponse)
            || !($response = $event->getResponse() instanceof HttpResponse)
        ) {
            return;
        }

        $rbacOptions = $this->rbacManager->getRbacOptions($exception->getPermissionName());
        if (count($rbacOptions->getRedirectStrategy()) == 0) {
            return;
        }

        $router = $event->getRouter();

        $options = new RedirectStrategyOptions($rbacOptions->getRedirectStrategy());

        $rbacService = $this->rbacManager->getService($exception->getPermissionName());

        if ([$rbacService->getGuestRole()] !== $rbacService->flattenRoles($rbacService->getIdentityRoles())) {
            if (!$options->getRedirectWhenConnected()) {
                return;
            }

            $redirectRoute = $options->getRedirectToRouteConnected();
        } else {
            $redirectRoute = $options->getRedirectToRouteDisconnected();
        }

        $uri = $router->assemble([], ['name' => $redirectRoute]);

        if ($options->getAppendPreviousUri()) {
            $redirectKey = $options->getPreviousUriQueryKey();
            $previousUri = $event->getRequest()->getUriString();

            $uri = $router->assemble(
                [],
                [
                    'name' => $redirectRoute,
                    'query' => [$redirectKey => $previousUri]
                ]
            );
        }

        $response = $event->getResponse() ?: new HttpResponse();

        $response->getHeaders()->addHeaderLine('Location', $uri);
        $response->setStatusCode(302);

        $event->setResponse($response);
        $event->setResult($response);
    }
}
