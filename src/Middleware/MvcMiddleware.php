<?php
namespace Core42\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Mvc\Application;
use Zend\Mvc\ResponseSender\SendResponseEvent;
use Zend\Psr7Bridge\Psr7Response;
use Zend\Stdlib\ArrayUtils;
use Zend\Stratigility\MiddlewareInterface;

class MvcMiddleware implements MiddlewareInterface
{

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param \Closure $callback
     */
    public function setCallback(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $appConfig = require getcwd() . '/config/application.config.php';
        $developmentConfig = require getcwd() . '/config/development.config.php';

        if (!empty($developmentConfig)) {
            $appConfig = ArrayUtils::merge($appConfig, $developmentConfig);
        }

        /** @var Application $application */
        $application = Application::init($appConfig);

        $path = $request->getUri()->getPath();
        if (strlen($application->getRequest()->getBasePath())
            && strpos($path, $application->getRequest()->getBasePath()) === false
        ) {
            return $out($request, $response);
        }

        $path = str_replace($application->getRequest()->getBasePath(), "", $path);

        $application
            ->getServiceManager()
            ->get('SendResponseListener')
            ->getEventManager()
            ->attach(SendResponseEvent::EVENT_SEND_RESPONSE, function (SendResponseEvent $event) {
                $event->stopPropagation(true);
            }, -900);

        if ($this->callback instanceof \Closure) {
            call_user_func_array($this->callback, [
                $path,
                $application,
                $request,
                $response
            ]);
        }

        $application->run();

        $response = $application->getResponse();


        return Psr7Response::fromZend($response);
    }
}
