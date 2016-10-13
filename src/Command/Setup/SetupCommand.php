<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Command\Setup;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class SetupCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     *
     */
    protected function execute()
    {

        $config = $this->getServiceManager()->get('config');

        foreach ($config['cli_setup_commands'] as $cmd) {
            $this->getCommand($cmd)->run();
            $this->consoleOutput("");
        }
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {

    }
}
