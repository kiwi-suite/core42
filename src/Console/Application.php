<?php
/**
 * core42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Console;

use Zend\Console\Adapter\AdapterInterface;
use ZF\Console\Dispatcher;
use ZF\Console\RouteCollection;

class Application extends \ZF\Console\Application
{
    /**
     * Application constructor.
     * @param string $name
     * @param string $version
     * @param array|\Traversable $routes
     * @param AdapterInterface $console
     * @param Dispatcher $dispatcher
     */
    public function __construct($name, $version, $routes, AdapterInterface $console, Dispatcher $dispatcher)
    {
        parent::__construct($name, $version, $routes, $console, $dispatcher);
        $this->banner = null;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param Dispatcher $dispatcher
     */
    protected function setupAutocompleteCommand(RouteCollection $routeCollection, Dispatcher $dispatcher)
    {

    }

    /**
     * @param RouteCollection $routeCollection
     * @param Dispatcher $dispatcher
     */
    protected function setupVersionCommand(RouteCollection $routeCollection, Dispatcher $dispatcher)
    {

    }
}
