<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Seeding;

use Core42\Command\ConsoleAwareInterface;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\I18n\Validator\Alnum;
use ZF\Console\Route;

class MakeCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $name;

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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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

        if (empty($this->name)) {
            $this->addError("name", "name isn't set");

            return;
        }

        $alnum = new Alnum();
        if (!$alnum->isValid($this->name)) {
            $this->addError("name", "name '".$this->name."' isn't valid");

            return;
        }

        $seedingDirs = $this->getSeedingDirectories();

        if (!in_array($this->directory, $seedingDirs)) {
            $this->addError("directory", "directory '".$this->directory."' is not inside a migration directory");

            return;
        }

        $checkArray = array();
        $seeds = $this->getAllSeeds();
        foreach ($seeds as $_seed) {
            $checkArray[] = $_seed['name'];
        }

        if (in_array($this->name, $checkArray)) {
            $this->addError("name", "name with '".$this->name."' already exists");

            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $seedingName = 'Seeding' . ucfirst($this->name);

        $camelCase = new CamelCaseToUnderscore();
        $filename = $this->directory . strtolower($camelCase->filter($this->name)) . '.php';

        $classGenerator = new ClassGenerator($seedingName);

        $classGenerator->addMethod("seed", array(
            new ParameterGenerator('serviceManager', 'Zend\ServiceManager\ServiceManager')
        ), MethodGenerator::FLAG_PUBLIC, "\n");

        file_put_contents($filename, "<?php\n" . $classGenerator->generate());

        $this->consoleOutput("'{$seedingName}' created at '{$filename}'");
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setDirectory($route->getMatchedParam('directory'));
        $this->setName($route->getMatchedParam('name'));
    }
}
