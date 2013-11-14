<?php
namespace Core42\Command\Seeding;

use Core42\Command\AbstractCommand;
use Zend\Validator\File\Extension;

class SeedCommand extends AbstractCommand
{
    private $seedingDirectory;


    public function preExecute()
    {
        $config = $this->getServiceManager()->get("Config");
        if (!is_dir($config['seeding']['seeding_dir'])) {
            mkdir($config['seeding']['seeding_dir'], 0777, true);
        }

        $this->seedingDirectory = $config['seeding']['seeding_dir'];
    }

    public function execute()
    {
        $fileValidator = new Extension("php");

        $dir = dir($this->seedingDirectory);
        $files = array();
        while ($file = $dir->read()) {
            if (!$fileValidator->isValid($this->seedingDirectory . $file)) {
                continue;
            }
            $files[] = $file;
        }

        foreach ($files as $file) {
            $className = 'Seeding\Seeding' . ucfirst(str_ireplace(".php", "", $file));
            require_once $this->seedingDirectory . $file;
            $obj = new $className;
            $obj->seed($this->getServiceManager());
        }
    }
}
