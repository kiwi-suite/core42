<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\CodeGenerator;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Db\Metadata\Metadata;
use Zend\Filter\Word\UnderscoreToCamelCase;
use ZF\Console\Route;

class GenerateDbClassesCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $all;

    /**
     * @var bool
     */
    protected $generateGetterSetter = false;

    /**
     * @var string
     */
    protected $adapterName = 'Db\Master';

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
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

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
     * @param string $adapterName
     * @return $this
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;

        return $this;
    }

    /**
     * @param string $all
     */
    public function setAll($all)
    {
        $this->all = $all;
    }

    /**
     * @param boolean $generateGetterSetter
     */
    public function setGenerateGetterSetter($generateGetterSetter)
    {
        $this->generateGetterSetter = $generateGetterSetter;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (empty($this->directory)) {
            $this->addError('directory', "directory parameter not set");
            return;
        }

        if (empty($this->namespace)) {
            $this->addError('namespace', "namespace parameter not set");
            return;
        }

        if ($this->all !== null && !empty($this->table) && !empty($this->name)) {
            $this->addError('all', "both usage of name/table arguments and --all argument is not allowed");
        }
        if ($this->all === null && empty($this->table) && empty($this->name)) {
            $this->addError('all', "Whether name/table arguments or --all argument are missing");
            return;
        }

        if (!$this->getServiceManager()->has($this->adapterName)) {
            $this->addError("adapter", "adapter '".$this->adapterName."' not found");

            return;
        }

        if (!is_dir($this->directory)) {
            $this->addError('directory', "directory '".$this->directory."' doesn't exist");
            return;
        }

        if (!is_dir($this->directory . "/" . 'Model')) {
            $this->addError('directory', "directory '".$this->directory."/Model' doesn't exist");
            return;
        }

        if (!is_dir($this->directory . "/" . 'TableGateway')) {
            $this->addError('directory', "directory '".$this->directory."/TableGateway' doesn't exist");
            return;
        }

        if (!is_writable($this->directory. "/" . 'Model')) {
            $this->addError("directory", "directory '".$this->directory."/Model' isn't writeable");
            return;
        }

        if (!is_writable($this->directory. "/" . 'TableGateway')) {
            $this->addError("directory", "directory '".$this->directory."/TableGateway' isn't writeable");
            return;
        }

        $this->directory = rtrim($this->directory, '/') . '/';
    }

    /**
     *
     */
    protected function execute()
    {
        if ($this->all !== null) {
            $adapter = $this->getServiceManager()->get($this->adapterName);
            $metadata = new Metadata($adapter);
            $tables = $metadata->getTableNames();

            $filter = new UnderscoreToCamelCase();

            foreach ($tables as $table) {
                if (in_array($table, ['migrations'])) {
                    continue;
                }
                if ($this->all != '*' && substr($table, 0, strlen($this->all)) != $this->all) {
                    continue;
                }

                $this->consoleOutput('Generate files for: ' . $table);

                $name = ucfirst($filter->filter(strtolower($table)));
                $this->generate($name, $table);
            }
        } else {
            $this->generate($this->name, $this->table);
        }
    }

    /**
     * @param string $name
     * @param string $table
     * @throws \Exception
     */
    protected function generate($name, $table)
    {
        $modelClassName = $this->namespace . '\\Model\\' . $name;
        $tableGatewayClassName = $this->namespace . '\\TableGateway\\' . $name . 'TableGateway';

        $modelDirectory = $this->directory . 'Model/';
        $tableGatewayDirectory = $this->directory . 'TableGateway/';

        /** @var GenerateModelCommand $generateModel */
        $generateModel = $this->getCommand('Core42\CodeGenerator\GenerateModel');
        $generateModel->setAdapterName($this->adapterName)
            ->setDirectory($modelDirectory)
            ->setClassName($modelClassName)
            ->setTableName($table)
            ->setGenerateSetterGetter($this->generateGetterSetter)
            ->run();

        /** @var GenerateTableGatewayCommand $generateTableGateway */
        $generateTableGateway = $this->getCommand('Core42\CodeGenerator\GenerateTableGateway');
        $generateTableGateway->setAdapterName($this->adapterName)
            ->setDirectory($tableGatewayDirectory)
            ->setClassName($tableGatewayClassName)
            ->setTableName($table)
            ->setModel($modelClassName)
            ->run();
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setDirectory($route->getMatchedParam("directory"));
        $this->setNamespace($route->getMatchedParam("namespace"));

        $table = $route->getMatchedParam("table");
        if (!empty($table)) {
            $this->setTable($table);
        }

        $name = $route->getMatchedParam("name");
        if (!empty($name)) {
            $this->setName($name);
        }

        $this->setAll($route->getMatchedParam('all', null));

        $this->setGenerateGetterSetter($route->getMatchedParam("getter-setter"));

        $adapterName = $route->getMatchedParam('adapter');
        if (!empty($adapterName)) {
            $this->setAdapterName($adapterName);
        }
    }
}
