<?php
namespace Core42\Command\Migration;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleOutputInterface;
use Core42\Db\Migration\Container;
use Core42\Migration\Migration;
use Zend\Validator\File\Extension;

class ResetCommand extends AbstractCommand implements ConsoleOutputInterface
{
    /**
     * @var string
     */
    private $migrationDirectory;

    /**
     * @var Container
     */
    private $migrationContainer;

    /**
     *
     */
    protected function preExecute()
    {
        $config = $this->getServiceManager()->get("Config");
        if (!is_dir($config['migration']['migration_dir'])) {
            mkdir($config['migration']['migration_dir'], 0777, true);
        }
        $this->migrationDirectory = rtrim($config['migration']['migration_dir'], "/") . "/";

        $this->migrationContainer = new Container();
    }

    /**
     *
     */
    protected function execute()
    {

        $fileValidator = new Extension("php");

        $dir = dir($this->migrationDirectory);
        $files = array();
        while ($file = $dir->read()) {
            if (!$fileValidator->isValid($this->migrationDirectory . $file)) {
                continue;
            }
            $files[] = $file;
        }
        sort($files, SORT_NUMERIC);
        $files = array_reverse($files);

        foreach ($files as $file) {
            $version = str_ireplace(".php", "", $file);
            require_once $this->migrationDirectory . $file;
            $className = 'Migrations\Migration'.$version;

            $migration = new Migration('Db\Master', $version);
            $this->migrationContainer->addMigration($migration);

            $migrationVersion = new $className();
            $migrationVersion->down($migration);
        }

        $this->migrationContainer->execute($this->getServiceManager(), 'down');
    }

    /**
     *
     */
    public function publishToConsole()
    {
        /** @var $console \Zend\Console\Adapter\AdapterInterface */
        $console = $this->getServiceManager()->get("Console");

        if ($this->migrationContainer->count() == 0) {
            $console->writeLine("Nothing to rollback");
            return;
        }

        /** @var $migration Migration */
        foreach ($this->migrationContainer as $migration) {
            $console->writeLine("Migration " . $migration->getVersion() . " rolled back");
        }
    }
}
