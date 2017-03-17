<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 11:47
 */

namespace Core42Test\View\Helper\Service;


use Core42\View\Helper\Params;
use Core42\View\Helper\Service\ParamsFactory;
use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\DispatchListener;
use Zend\Mvc\HttpMethodListener;
use Zend\Mvc\MiddlewareListener;
use Zend\Mvc\RouteListener;
use Zend\Mvc\SendResponseListener;
use Zend\Router\SimpleRouteStack;
use Zend\ServiceManager\ServiceManager;


class ParamsFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $paramsFactory = new ParamsFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService('Request', new Request());
        $serviceManager->setService('Router', new SimpleRouteStack());
        $serviceManager->setService('RouteListener', $this->prophesize(RouteListener::class));
        $serviceManager->setService('MiddlewareListener', $this->prophesize(MiddlewareListener::class));
        $serviceManager->setService('DispatchListener', $this->prophesize(DispatchListener::class));
        $serviceManager->setService('HttpMethodListener', $this->prophesize(HttpMethodListener::class));
        $serviceManager->setService('ViewManager', $this->prophesize(RouteListener::class));
        $serviceManager->setService('SendResponseListener', $this->prophesize(SendResponseListener::class));

        $application = new Application(
            $serviceManager,
            new EventManager(),
            new Request(),
            new Response()
        );
        $application->bootstrap();

        $serviceManager->setService('Application', $application);

        $this->assertInstanceOf(
            Params::class,
            $paramsFactory($serviceManager, Params::class, [])
        );
    }
}
