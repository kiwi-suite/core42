<?php
namespace Core42\Command\Seeding;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleOutputInterface;
use Zend\Validator\File\Extension;

class SeedCommand extends AbstractCommand implements ConsoleOutputInterface
{
    /**
     * @var string
     */
    private $seedingDirectory;

    /**
     * @var array
     */
    private $files = array();

    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
     * @return \Core42\Command\Seeding\SeedCommand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function preExecute()
    {
        $config = $this->getServiceManager()->get("Config");
        if (!is_dir($config['seeding']['seeding_dir'])) {
            mkdir($config['seeding']['seeding_dir'], 0777, true);
        }

        $this->seedingDirectory = rtrim($config['seeding']['seeding_dir'], '/') . '/';

        if (!empty($this->name)) {
            if (file_exists($this->seedingDirectory . $this->name . '.php')) {
                $this->files[] = $this->name . '.php';
            }
        } else {
            $fileValidator = new Extension("php");
            $dir = dir($this->seedingDirectory);
            while ($file = $dir->read()) {
                if (!$fileValidator->isValid($this->seedingDirectory . $file)) {
                    continue;
                }
                $this->files[] = $file;
            }
        }
    }

    public function execute()
    {
        foreach ($this->files as $file) {
            $className = 'Seeding\Seeding' . ucfirst(str_ireplace(".php", "", $file));
            require_once $this->seedingDirectory . $file;
            $obj = new $className;
            $obj->seed($this->getServiceManager());
        }
    }

    public function publishToConsole()
    {
        /** @var $console \Zend\Console\Adapter\AdapterInterface */
        $console = $this->getServiceManager()->get("Console");

        if (empty($this->files)) {
            $console->writeLine("No seeding file(s) found");
            return;
        }

        foreach ($this->files as $file) {
            $console->writeLine("Seeding " . str_replace(".php", "", $file) . " seeded");
        }
    }
}
