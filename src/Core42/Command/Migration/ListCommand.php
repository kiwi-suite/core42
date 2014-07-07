<?php
namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareInterface;
use ZF\Console\Route;

class ListCommand extends AbstractCommand implements ConsoleAwareInterface
{

    protected function configure()
    {
        $this->setupTable();
    }

    /**
     *
     */
    protected function execute()
    {
        $migrationList = $this->getAllMigrations();

        foreach ($migrationList as $migration) {
            if ($migration['migrated'] === null) {
                $this->consoleOutput("<comment>" .$migration['name'].' (pending)</comment>');
            } else {
                $this->consoleOutput("<info>" .$migration['name'].' ('.$migration['migrated']->getCreated()->format('Y-m-d H:i:s').')</info>');
            }

            $this->consoleOutput($migration['filename']);
            $this->consoleOutput("");
        }

    }

    public function consoleSetup(Route $route){}
}
