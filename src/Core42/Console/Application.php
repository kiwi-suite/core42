<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Console;

use Zend\Console\ColorInterface as Color;

class Application extends \ZF\Console\Application
{
    public function showUsageMessage($name = null)
    {
        $console = $this->console;

        if ($name === null) {
            $console->writeLine('Available commands:', Color::GREEN);
            $console->writeLine('');
        }

        foreach ($this->routeCollection as $route) {
            if ($name === $route->getName()) {
                $this->showUsageMessageForRoute($route);
                return;
            }

            if ($name !== null) {
                continue;
            }

            $routeName = $route->getName();
            $console->write(' ' . $routeName, Color::GREEN);
            $console->writeLine(str_repeat(" ", max(0, 30 - strlen($routeName))) . $route->getShortDescription());
        }

        if ($name) {
            $this->showUnrecognizedRouteMessage($name);
            return;
        }
    }
}
