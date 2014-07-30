<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Migration;

use Core42\Command\ConsoleAwareInterface;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use ZF\Console\Route;

class MakeCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @param string $directory
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        $this->directory = rtrim($this->directory, '/') . '/';

        if (!is_dir($this->directory)) {
            $this->addError('directory', "directory '".$this->directory."' doesn't exist");

            return;
        }

        if (!is_writable($this->directory)) {
            $this->addError("directory", "directory '".$this->directory."' isn't writeable");

            return;
        }

        $migrationDirs = $this->getMigrationDirectories();

        if (!in_array($this->directory, $migrationDirs)) {
            $this->addError("directory", "directory '".$this->directory."' is not inside a migration directory");

            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        do {
            $date = new \DateTime();
            $migrationName = 'Migration' . $date->format('YmdHis');
            $filename = $this->directory . $date->format('Y-m-d\tHis') . '.php';
        } while (file_exists($filename));

        $classGenerator = new ClassGenerator($migrationName);

        $classGenerator->addMethod("up", array(
            new ParameterGenerator('serviceManager', 'Zend\ServiceManager\ServiceManager')
        ), MethodGenerator::FLAG_PUBLIC, "\n");

        $classGenerator->addMethod("down", array(
            new ParameterGenerator('serviceManager', 'Zend\ServiceManager\ServiceManager')
        ), MethodGenerator::FLAG_PUBLIC, "\n");

        file_put_contents($filename, "<?php\n" . $classGenerator->generate());

        $this->consoleOutput("'{$migrationName}' created at '{$filename}'");
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setDirectory($route->getMatchedParam('directory'));
    }
}
