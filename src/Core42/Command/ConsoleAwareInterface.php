<?php
namespace Core42\Command;

use ZF\Console\Route;

interface ConsoleAwareInterface
{
    public function consoleSetup(Route $route);
}
