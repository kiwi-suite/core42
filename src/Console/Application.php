<?php
/**
 * core42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Console;

use ZF\Console\Dispatcher;
use ZF\Console\DispatcherInterface;
use ZF\Console\RouteCollection;

class Application extends \ZF\Console\Application
{
    /**
     * Sets up the default autocomplete command
     *
     * Creates the route, and maps the command.
     *
     * @param RouteCollection $routeCollection
     * @param DispatcherInterface $dispatcher
     */
    protected function setupAutocompleteCommand(RouteCollection $routeCollection, DispatcherInterface $dispatcher)
    {

    }

    /**
     * Sets up the default version command
     *
     * Creates the route, and maps the command.
     *
     * @param RouteCollection $routeCollection
     * @param DispatcherInterface $dispatcher
     */
    protected function setupVersionCommand(RouteCollection $routeCollection, DispatcherInterface $dispatcher)
    {
        
    }
}
