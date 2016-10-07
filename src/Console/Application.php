<?php
/**
 * core42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Console;

use Zend\Console\ColorInterface;
use ZF\Console\DispatcherInterface;
use ZF\Console\RouteCollection;
use Zend\Console\Adapter\AdapterInterface as CliConsole;

class Application extends \ZF\Console\Application
{
    protected $groups = [];

    public function __construct($name, $version, $routes, CliConsole $console, DispatcherInterface $dispatcher)
    {
        foreach ($routes as &$route) {
            $group = (!empty($route['group'])) ? $route['group'] : '*';
            unset($route['group']);

            if (!isset($this->groups[$group])) {
                $this->groups[$group] = [];
            }

            $this->groups[$group][] = $route['name'];
        }
        uksort($this->groups, function ($value1, $value2) {
            if ($value1 == '*' && $value2 != '*') {
                return 1;
            }
            if ($value1 != '*' && $value2 == '*') {
                return -1;
            }

            return strcmp($value1, $value2);
        });
        foreach ($this->groups as &$commands) {
            sort($commands);
        }

        parent::__construct($name, $version, $routes, $console, $dispatcher);
    }

    /**
     * @param null $name
     */
    public function showUsageMessage($name = null)
    {
        $console = $this->console;

        if ($name === null) {
            $console->writeLine('Available commands:', ColorInterface::YELLOW);
            $console->writeLine('');
        } elseif (in_array($name, $this->routeCollection->getRouteNames())) {
            $route = $this->routeCollection->getRoute($name);
            $this->showUsageMessageForRoute($route);
            return;
        }


        $maxSpaces = $this->calcMaxString($this->routeCollection->getRouteNames()) +  2;

        foreach ($this->groups as $groupName => $groupCommands) {
            $console->writeLine("");
            $groupName = ($groupName != "*") ? $groupName : "misc";
            $console->writeLine(ucfirst($groupName) . ':', ColorInterface::YELLOW);

            foreach ($groupCommands as $routeName) {
                $route = $this->routeCollection->getRoute($routeName);

                if ($name !== null) {
                    continue;
                }

                $routeName = $route->getName();
                $spaces = $maxSpaces - strlen($routeName);
                $console->write(' ' . $routeName, ColorInterface::GREEN);
                $console->writeLine(str_repeat(' ', $spaces) . $route->getShortDescription());
            }
        }

        if ($name) {
            $this->showUnrecognizedRouteMessage($name);
            return;
        }
    }

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
