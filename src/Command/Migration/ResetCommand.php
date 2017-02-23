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
use Core42\TableGateway\MigrationTableGateway;
use ZF\Console\Route;

class ResetCommand extends AbstractMigrationCommand
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
        /** @var \Core42\TableGateway\MigrationTableGateway $migrationTableGateway */
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get(MigrationTableGateway::class);

        $migrationList = \array_reverse($this->getAllMigrations());

        $migrationCounter = 0;

        foreach ($migrationList as $migration) {
            if ($migration['migrated'] === null) {
                continue;
            }

            $migration['instance']->down($this->getServiceManager());

            $migrationTableGateway->delete(['name' => $migration['name']]);
            $migrationCounter++;

            $this->consoleOutput("Migration {$migration['name']} rolled back");
        }
        $this->consoleOutput('');

        if ($migrationCounter > 0) {
            $this->consoleOutput("All {$migrationCounter} Migrations reseted");

            return;
        }

        $this->consoleOutput('Nothing to reset');
    }

    /**
     * @param Route $route
     * @return mixed|void
     */
    public function consoleSetup(Route $route)
    {
    }
}
