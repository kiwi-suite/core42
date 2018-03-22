<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
