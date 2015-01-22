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
use Core42\Command\ConsoleAwareInterface;
use ZF\Console\Route;

class GenerateDbClassesCommand extends AbstractCommand implements ConsoleAwareInterface
{
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

    protected function preExecute()
    {
        if (empty($this->directory)) {
            $this->addError('directory', "directory parameter not set");
            return;
        }

        if (empty($this->table)) {
            $this->addError('table', "table parameter not set");
            return;
        }

        if (empty($this->namespace)) {
            $this->addError('namespace', "namespace parameter not set");
            return;
        }

        if (empty($this->name)) {
            $this->addError('name', "name parameter not set");
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

    protected function execute()
    {
        $modelClassName = $this->namespace . '\\Model\\' . $this->name;
        $tableGatewayClassName = $this->namespace . '\\TableGateway\\' . $this->name . 'TableGateway';

        $modelDirectory = $this->directory . 'Model/';
        $tableGatewayDirectory = $this->directory . 'TableGateway/';

        /** @var GenerateModelCommand $generateModel */
        $generateModel = $this->getCommand('Core42\CodeGenerator\GenerateModel');
        $generateModel->setAdapterName($this->adapterName)
            ->setDirectory($modelDirectory)
            ->setClassName($modelClassName)
            ->setTableName($this->table)
            ->run();

        /** @var GenerateTableGatewayCommand $generateTableGateway */
        $generateTableGateway = $this->getCommand('Core42\CodeGenerator\GenerateTableGateway');
        $generateTableGateway->setAdapterName($this->adapterName)
            ->setDirectory($tableGatewayDirectory)
            ->setClassName($tableGatewayClassName)
            ->setTableName($this->table)
            ->setModel($modelClassName)
            ->run();
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setTable($route->getMatchedParam("table"));
        $this->setDirectory($route->getMatchedParam("directory"));
        $this->setName($route->getMatchedParam("name"));
        $this->setNamespace($route->getMatchedParam("namespace"));

        $adapterName = $route->getMatchedParam('adapter');
        if (!empty($adapterName)) {
            $this->setAdapterName($adapterName);
        }
    }
}
