<?php
namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareInterface;
use ZF\Console\Route;

class ResetCommand extends AbstractCommand implements ConsoleAwareInterface
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
        /** @var \Core42\TableGateway\MigrationTableGateway $migrationTableGateway */
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Migration');

        $migrationList = array_reverse($this->getAllMigrations());

        $migrationCounter = 0;

        foreach ($migrationList as $migration) {
            if ($migration['migrated'] === null) {
                continue;
            }

            $migration['instance']->down($this->getServiceManager());

            $migrationTableGateway->delete(array('name' => $migration['name']));
            $migrationCounter++;
            $this->consoleOutput("Migration {$migration['name']} rolled back");
        }
        $this->consoleOutput("");
        if ($migrationCounter == 0) {
            $this->consoleOutput("Nothing to reset");
        } else {
            $this->consoleOutput("All {$migrationCounter} Migrations reseted");
        }
    }

    /**
     * @param Route $route
     * @return mixed|void
     */
    public function consoleSetup(Route $route) {}
}
