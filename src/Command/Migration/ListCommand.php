<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class ListCommand extends AbstractMigrationCommand
{
    use ConsoleAwareTrait;

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
                $this->consoleOutput('<comment>' . $migration['name'] . ' (pending)</comment>');
            } else {
                $migrationDate = $migration['migrated']->getCreated()->format('Y-m-d H:i:s');
                $this->consoleOutput(
                    "<info>{$migration['name']} ({$migrationDate})</info>"
                );
            }

            $this->consoleOutput($migration['filename']);
            $this->consoleOutput('');
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
