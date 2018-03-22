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
