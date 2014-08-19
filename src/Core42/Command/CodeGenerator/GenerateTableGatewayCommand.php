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

class GenerateTableGatewayCommand extends AbstractCommand implements ConsoleAwareInterface
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
        } catch (ServiceNotFoundException $e) {
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

            foreach ($tables as $table){
                $this->generateTableGatewayClass($table);
            }

        } else {
            $metadata->getTable($this->tableName);
            $this->generateTableGatewayClass($this->tableName);
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

    protected function generateTableGatewayClass($table)
    {
        $class = new Generator\ClassGenerator();
        $class->setNamespaceName($this->namespace . "\\TableGateway")
            ->addUse('Core42\Db\TableGateway\AbstractTableGateway');

        $filter = new UnderscoreToCamelCase();
        $className = ucfirst($filter->filter(strtolower($table)));

        $tableGatewayName = $className . "TableGateway";

        $class->setName($tableGatewayName)
            ->setExtendedClass('AbstractTableGateway');

        $property = new Generator\PropertyGenerator("table");
        $property->setDefaultValue($table);
        $property->setDocBlock(new Generator\DocBlockGenerator(null, null, array(new Generator\DocBlock\Tag\GenericTag('var', 'string'))));
        $property->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);

        $class->addPropertyFromGenerator($property);

        $property = new Generator\PropertyGenerator("modelPrototype");
        $property->setDefaultValue($this->namespace . "\\Model\\" . $className);
        $property->setDocBlock(new Generator\DocBlockGenerator(null, null, array(new Generator\DocBlock\Tag\GenericTag('var', 'string'))));
        $property->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);

        $class->addPropertyFromGenerator($property);

        $property = new Generator\PropertyGenerator("databaseTypeMap");
        $property->setDefaultValue(array(), Generator\ValueGenerator::TYPE_ARRAY, Generator\ValueGenerator::OUTPUT_SINGLE_LINE);
        $property->setDocBlock(new Generator\DocBlockGenerator(null, null, array(new Generator\DocBlock\Tag\GenericTag('var', 'array'))));
        $property->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);

        $class->addPropertyFromGenerator($property);

        $property = new Generator\PropertyGenerator("underscoreSeparatedKeys");
        $property->setDefaultValue(false);
        $property->setDocBlock(new Generator\DocBlockGenerator(null, null, array(new Generator\DocBlock\Tag\GenericTag('var', 'bool'))));
        $property->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);

        $class->addPropertyFromGenerator($property);

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($class),
            )
        );

        $filename = $this->directory . "/" . $tableGatewayName . '.php';
        if (!file_exists($filename) || $this->overwrite === true) {
            file_put_contents($filename, $file->generate());
            $this->consoleOutput("Generated {$tableGatewayName}");
        } else {
            $this->consoleOutput("Skipped {$tableGatewayName} - it all ready exists. Use --override!");
        }
    }
}
