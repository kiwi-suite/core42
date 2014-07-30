<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareInterface;
use ZF\Console\Route;

class ListCommand extends AbstractCommand implements ConsoleAwareInterface
{

    /**
     * @throws \Exception
     */
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

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {

    }
}
