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

namespace Core42\Command\Maintenance;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class MaintenanceCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    private $enable = false;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @param bool $enable
     * @return $this
     */
    public function enableMaintenance($enable)
    {
        $this->enable = (bool) $enable;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!is_dir('data/maintenance')) {
            $created = mkdir('data/maintenance', 0777, true);
            if ($created === false) {
                $this->addError('directory', "directory 'data/maintenance' can't be created");
            }
        }

        if (!is_writable('data/maintenance')) {
            $this->addError('directory', "directory 'data/maintenance' isn't writable");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        if ($this->enable === true && !file_exists('data/maintenance/on')) {
            touch('data/maintenance/on');
            $this->consoleOutput('maintenance mode enabled');

            return;
        } elseif ($this->enable === false && file_exists('data/maintenance/on')) {
            unlink('data/maintenance/on');
            $this->consoleOutput('maintenance mode disabled');

            return;
        }

        $this->consoleOutput('<info>maintenance mode already ' . (($this->enable) ? 'enabled' : 'disabled') . '</info>');
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->enableMaintenance($route->getMatchedParam('on'));
    }
}
