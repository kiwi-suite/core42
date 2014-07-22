<?php
namespace Core42\Command;

use ZF\Console\Route;

interface ConsoleAwareInterface
{
    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route);
}
