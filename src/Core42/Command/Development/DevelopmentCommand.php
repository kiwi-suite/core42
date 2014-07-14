<?php
namespace Core42\Command\Development;

use Core42\Command\ConsoleAwareInterface;
use Core42\Command\Migration\AbstractCommand;
use ZF\Console\Route;

class DevelopmentCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var boolean
     */
    private $enable = false;

    /**
     * @param boolean $enable
     * @return $this
     */
    public function enableDevelopment($enable)
    {
        $this->enable = (boolean) $enable;

        return $this;
    }

    protected  function preExecute()
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
            $this->consoleOutput("development mode enabled");
        } elseif ($this->enable === false && file_exists('data/development/on')) {
            unlink('data/development/on');
            $this->consoleOutput("development mode disabled");
        } else {
            $this->consoleOutput("<info>development mode already ".(($this->enable) ? 'enabled': 'disabled')."</info>");
        }
    }

    public function consoleSetup(Route $route)
    {
        $this->enableDevelopment($route->getMatchedParam("on"));
    }
}
