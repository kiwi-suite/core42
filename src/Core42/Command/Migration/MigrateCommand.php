<?php
namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareInterface;
use Core42\Model\Migration;
use ZF\Console\Route;

class MigrateCommand extends AbstractCommand implements ConsoleAwareInterface
{
    private $limit = -1;

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }


    protected function configure()
    {
        $this->setupTable();
    }

    protected function preExecute()
    {
        $this->limit = (int) $this->limit;
        if (empty($this->limit) || $this->limit < 1) {
            $this->limit = -1;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        /** @var \Core42\TableGateway\MigrationTableGateway $migrationTableGateway */
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Migration');

        $migrationList = $this->getAllMigrations();

        $migrationCounter = 0;

        foreach ($migrationList as $migration) {
            if ($this->limit > 0 && $migrationCounter >= $this->limit) {
                break;
            }
            if ($migration['migrated'] !== null) {
                continue;
            }

            $migration['instance']->up($this->getServiceManager());

            $migrationObject = new Migration();
            $migrationObject->setName($migration['name'])
                                ->setCreated(new \DateTime());

            $migrationTableGateway->insert($migrationObject);
            $migrationCounter++;
            $this->consoleOutput("Migration {$migration['name']} migrated");
        }
        $this->consoleOutput("");
        if ($migrationCounter == 0) {
            $this->consoleOutput("Nothing to migrate");
        } else {
            $this->consoleOutput("{$migrationCounter} Migrations migrated");
        }
    }

    public function consoleSetup(Route $route)
    {
        $this->setLimit($route->getMatchedParam('limit', -1));
    }
}
