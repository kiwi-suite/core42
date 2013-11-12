<?php
namespace Core42\Command\Migration;

use Core42\Command\AbstractCommand;
use Core42\Db\Migration\Container;
use Core42\Migration\Migration;
use Zend\Validator\File\Extension;

class ResetCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $migrationDirectory;

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

        $migrationContainer = new Container();

        foreach ($files as $file) {
            $version = str_ireplace(".php", "", $file);
            require_once $this->migrationDirectory . $file;
            $className = 'Migrations\Migration'.$version;

            $migration = new Migration('Db\Master', $version);
            $migrationContainer->addMigration($migration);

            $migrationVersion = new $className();
            $migrationVersion->down($migration);
        }

        $migrationContainer->execute($this->getServiceManager(), 'down');
    }
}
