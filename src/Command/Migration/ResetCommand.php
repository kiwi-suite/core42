<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class ResetCommand extends AbstractCommand
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
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Migration');

        $migrationList = array_reverse($this->getAllMigrations());

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
        $this->consoleOutput("");

        if ($migrationCounter > 0) {
            $this->consoleOutput("All {$migrationCounter} Migrations reseted");

            return;
        }

        $this->consoleOutput("Nothing to reset");
    }

    /**
     * @param Route $route
     * @return mixed|void
     */
    public function consoleSetup(Route $route)
    {

    }
}
