<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command;

use Core42\Console\Console;
use ZF\Console\Route;

trait ConsoleAwareTrait
{

    /**
     * @param string $message
     */
    protected function consoleOutput($message)
    {
        if (!Console::isConsole()) {
            return;
        }

        Console::outputFilter($message);
    }

    /**
     * @param Route $route
     * @return void
     */
    abstract public function consoleSetup(Route $route);
}
