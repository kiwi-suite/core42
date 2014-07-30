<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
