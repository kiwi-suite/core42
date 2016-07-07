<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Cache;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class ClearAppCacheCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     *
     */
    protected function execute()
    {

    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {

    }
}
