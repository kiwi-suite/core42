<?php
namespace Core42\Controller\Cli;

use Core42\Command\Migration\MakeCommand;
use Core42\Command\Migration\MigrateCommand;
use Core42\Command\Migration\ResetCommand;
use Core42\Command\Migration\RollbackCommand;
use Zend\Mvc\Controller\AbstractActionController;

class MigrationController extends AbstractActionController
{

    public function makeAction()
    {
        /** @var $console \Zend\Console\Adapter\AdapterInterface */
        $console = $this->getServiceLocator()->get('console');

        $cmd = new MakeCommand();
        $cmd->run();

        $console->writeLine("migration generated in ".$cmd->getFilename());
    }

    public function migrateAction()
    {
        $cmd = new MigrateCommand();
        $cmd->run();
    }

    public function rollbackAction()
    {
        $cmd = new RollbackCommand();
        $cmd->run();
    }

    public function resetAction()
    {
        $cmd = new ResetCommand();
        $cmd->run();
    }
}
