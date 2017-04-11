<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Mvc\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;

class ExceptionStrategy extends AbstractListenerAggregate
{

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'prepareExceptionViewModel']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'prepareExceptionViewModel']);
    }

    /**
     * @param MvcEvent $e
     * @throws \Exception
     * @throws \Throwable
     */
    public function prepareExceptionViewModel(MvcEvent $e)
    {
        $error = $e->getError();
        if (empty($error)) {
            return;
        }

        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }

        $exception = $e->getParam('exception');
        if ($exception instanceof \Throwable) {
            throw $exception;
        }

        if ($exception instanceof \Exception) {
            throw $exception;
        }
    }
}
