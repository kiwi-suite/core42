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
     * @param null|int $color
     * @param null|int $bgColor
     */
    protected function consoleOutput($message, $color = null, $bgColor = null)
    {
        if (!Console::isConsole()) {
            return;
        }

        $message = Console::outputFilter($message);
        Console::getInstance()->writeLine($message, $color, $bgColor);
    }

    /**
     * @param string $message
     * @param null|int $color
     * @param null|int $bgColor
     */
    protected function consoleWrite($message, $color = null, $bgColor = null)
    {
        if (!Console::isConsole()) {
            return;
        }

        $message = Console::outputFilter($message);
        Console::getInstance()->write($message, $color, $bgColor);
    }

    /**
     * @param Route $route
     * @return void
     */
    abstract public function consoleSetup(Route $route);
}
