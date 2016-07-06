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
use Core42\Permission\Rbac\Guard\GuardInterface;
use Core42\Permission\Rbac\Options\UnauthorizedStrategyOptions;
use Core42\Permission\Rbac\RbacManager;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class UnauthorizedStrategy extends AbstractStrategy
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
        if (count($rbacOptions->getUnauthorizedStrategy()) == 0) {
            return;
        }

        $options = new UnauthorizedStrategyOptions($rbacOptions->getUnauthorizedStrategy());

        $model = new ViewModel();
        $model->setTemplate($options->getTemplate());

        switch ($event->getError()) {
            case GuardInterface::GUARD_UNAUTHORIZED:
                $model->setVariable('error', GuardInterface::GUARD_UNAUTHORIZED);
                break;

            default:
        }

        $response = $event->getResponse() ?: new HttpResponse();
        $response->setStatusCode(403);

        $event->setResponse($response);
        $event->setResult($model);
    }
}
