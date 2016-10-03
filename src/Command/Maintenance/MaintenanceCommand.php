<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Maintenance;

use Core42\Command\ConsoleAwareTrait;
use Core42\Command\Migration\AbstractCommand;
use ZF\Console\Route;

class MaintenanceCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var boolean
     */
    private $enable = false;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @param boolean $enable
     * @return $this
     */
    public function enableMaintenance($enable)
    {
        $this->enable = (boolean) $enable;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!is_dir('data/development')) {
            $created = mkdir('data/development', 0777, true);
            if ($created === false) {
                $this->addError('directory', "directory 'data/development' can't be created");
            }
        }

        if (!is_writable('data/development')) {
            $this->addError('directory', "directory 'data/development' isn't writable");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        if ($this->enable === true && !file_exists('data/maintenance/on')) {
            touch('data/maintenance/on');
            $this->consoleOutput("maintenance mode enabled");

            return;
        } elseif ($this->enable === false && file_exists('data/maintenance/on')) {
            unlink('data/maintenance/on');
            $this->consoleOutput("maintenance mode disabled");

            return;
        }

        $this->consoleOutput("<info>maintenance mode already ".(($this->enable) ? 'enabled': 'disabled')."</info>");
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->enableMaintenance($route->getMatchedParam("on"));
    }
}
