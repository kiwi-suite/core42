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

namespace Core42\Command\Development;

use Core42\Command\ConsoleAwareTrait;
use Core42\Command\Migration\AbstractCommand;
use ZF\Console\Route;

class DevelopmentCommand extends AbstractCommand
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
    public function enableDevelopment($enable)
    {
        $this->enable = (bool) $enable;

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
        if ($this->enable === true && !file_exists('data/development/on')) {
            touch('data/development/on');
            $this->consoleOutput('development mode enabled');

            return;
        } elseif ($this->enable === false && file_exists('data/development/on')) {
            unlink('data/development/on');
            $this->consoleOutput('development mode disabled');

            return;
        }

        $this->consoleOutput('<info>development mode already ' . (($this->enable) ? 'enabled' : 'disabled') . '</info>');
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->enableDevelopment($route->getMatchedParam('on'));
    }
}
