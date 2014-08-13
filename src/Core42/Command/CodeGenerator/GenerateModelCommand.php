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
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use ZF\Console\Route;
use Zend\Code\Generator;
use Zend\Code\Reflection;
use Zend\Db\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Filter\Word\UnderscoreToCamelCase;


class GenerateModelCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @type \Zend\Db\Adapter\Adapter
     */
    private $adapter;

    /**
     * @type string
     */
    private $adapterName = 'Db\Master';

    /**
     * @type string
     */
    private $namespace;

    /**
     * @type string
     */
    private $directory;

    /**
     * @type boolean
     */
    private $all;

    /**
     * @type string
     */
    private $tableName;

    /**
     * @type boolean
     */
    private $overwrite;

    /**
     * @param mixed $adapterName
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;
    }

    /**
     * @param mixed $all
     */
    public function setAll($all)
    {
        $this->all = (boolean) $all;
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $directory = rtrim($directory, '/');
        $this->directory = $directory;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param boolean $overwrite
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = (boolean) $overwrite;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (isset($this->all) && isset($this->tableName)) {
            $this->addError('all', "both usage of name argument and --all argument is missing");
        }
        if (!isset($this->all) && !isset($this->tableName)) {
            $this->addError('all', "Whether name argument or --all argument is missing");
            return;
        }

        if (!isset($this->namespace)) {
            $this->addError('namespace', "namespace parameter not set");
            return;
        }

        if (!isset($this->directory)) {
            $this->addError('directory', "directory parameter not set");
            return;
        }

        if (!is_dir($this->directory)) {
            $this->addError('directory', "directory '".$this->directory."' doesn't exist");
            return;
        }

        if (!is_writable($this->directory)) {
            $this->addError("directory", "directory '".$this->directory."' isn't writeable");
            return;
        }

        try {
            $this->adapter = $this->getServiceManager()->get($this->adapterName);
        } catch(ServiceNotFoundException $e) {
            $this->addError("adapter", "adapter '".$this->adapterName."' not found");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $metadata = new Metadata($this->adapter);

        if ($this->all) {
            $tables = $metadata->getTableNames();

            foreach($tables as $table){
                $this->generateModelClass($table);
            }

        } else {
            $metadata->getTable($this->tableName);
            $this->generateModelClass($this->tableName);
        }
    }

    /**
     * @param Route $route
     * @return mixed|void
     */
    public function consoleSetup(Route $route)
    {
        $this->setAll($route->getMatchedParam('all'));
        $this->setTableName($route->getMatchedParam('table'));

        $adapterName = $route->getMatchedParam('adapter');
        if (!empty($adapterName)) {
            $this->setAdapterName($adapterName);
        }

        $this->setNamespace($route->getMatchedParam('namespace'));
        $this->setOverwrite($route->getMatchedParam('overwrite'));
        $this->setDirectory($route->getMatchedParam('directory'));
    }

    protected function generateModelClass($table)
    {
        $metadata = new Metadata($this->adapter);

        $modelClass = new Generator\ClassGenerator();
        $modelClass->setNamespaceName($this->namespace . "\\Model")
            ->addUse('Core42\Model\AbstractModel');

        $filter = new UnderscoreToCamelCase();
        $modelName = ucfirst($filter->filter(strtolower($table)));

        $modelClass->setName($modelName)
            ->setExtendedClass('AbstractModel');

        $columns = $metadata->getColumns($table);

        $methods = array();
        foreach($columns as $column){
            /* @type \Zend\Db\Metadata\Object\ColumnObject $column */

            //setter
            $method = ucfirst($filter->filter($column->getName()));

            $docBlockParam = new Generator\DocBlock\Tag\ParamTag();
            $docBlockParam->setVariableName($column->getName());
            $docBlockParam->setTypes($this->getPropertyTypeByColumnObject($column));

            $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
            $docBlockReturn->setTypes('\\' . $this->namespace. '\\Model\\' . $modelName);

            $methods[] = new Generator\MethodGenerator(
                'set'.$method,
                array(
                    new Generator\ParameterGenerator(
                        $column->getName(),
                        null,
                        $column->getColumnDefault()
                    )
                ),
                Generator\MethodGenerator::FLAG_PUBLIC,
                implode("\n", array(
                    '$this->set(\''.$column->getName().'\', $'.$column->getName().');',
                    'return $this;'
                )),
                new Generator\DocBlockGenerator(
                    null, null, array(
                        $docBlockParam,
                        $docBlockReturn
                    )
                )
            );

            //getter
            $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
            $docBlockReturn->setTypes($this->getPropertyTypeByColumnObject($column));

            $methods[] = new Generator\MethodGenerator(
                'get'.$method,
                array(),
                Generator\MethodGenerator::FLAG_PUBLIC,
                "return \$this->get('".$column->getName()."');",
                new Generator\DocBlockGenerator(
                    null, null, array(
                        $docBlockReturn
                    )
                )
            );

        }

        $modelClass->addMethods($methods);

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($modelClass),
            )
        );

        $filename = $this->directory . "/" . $modelName . '.php';
        if (!file_exists($filename) || $this->overwrite === true){
            file_put_contents($filename, $file->generate());
            $this->consoleOutput("Generated {$modelName}");
        } else {
            $this->consoleOutput("Skipped {$modelName} - it all ready exists. Use --override!");
        }
    }

    protected function getPropertyTypeByColumnObject(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        switch($column->getDataType()){
            case "boolean":
            case "bool":
                return "boolean";
            case "enum":
                if (in_array($column->getErrata("permitted_values"), array(array("true", "false"), array("false", "true")))) {
                    return "boolean";
                }
                return "string";
            case "decimal":
            case "numeric":
            case "float":
            case "double":
                return "float";
            case "int":
            case "integer":
            case "tinyint":
            case "mediumint":
            case "bigint":
                return "int";
            case "datetime":
            case "date":
            case "timestamp":
                return '\DateTime';
            case "varchar":
            case "char":
            case "text":
            default:
                return "string";
        }
    }

}

