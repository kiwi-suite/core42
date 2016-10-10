<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Command\CodeGenerator;

use Core42\Command\AbstractCommand;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Code\Generator;

class GenerateTableGatewayCommand extends AbstractCommand
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $adapterName = 'Db\Master';

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $model;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @param mixed $adapterName
     * @return $this
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;

        return $this;
    }

    /**
     * @param mixed $directory
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @param mixed $tableName
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (empty($this->className)) {
            $this->addError('className', 'className parameter not set');

            return;
        }

        if (empty($this->directory)) {
            $this->addError('directory', 'directory parameter not set');

            return;
        }

        if (empty($this->tableName)) {
            $this->addError('tableName', 'tableName parameter not set');

            return;
        }

        if (empty($this->model)) {
            $this->addError('model', 'model parameter not set');

            return;
        }

        if (!is_dir($this->directory)) {
            $this->addError('directory', "directory '" . $this->directory . "' doesn't exist");

            return;
        }

        if (!is_writable($this->directory)) {
            $this->addError('directory', "directory '" . $this->directory . "' isn't writeable");

            return;
        }

        try {
            $this->adapter = $this->getServiceManager()->get($this->adapterName);
        } catch (ServiceNotFoundException $e) {
            $this->addError('adapter', "adapter '" . $this->adapterName . "' not found");
        }

        $this->directory = rtrim($this->directory, '/') . '/';
    }

    /**
     *
     */
    protected function execute()
    {
        $metadata = Factory::createSourceFromAdapter($this->adapter);
        $metadata->getTable($this->tableName);

        $parts = explode('\\', $this->className);
        $class = array_pop($parts);
        $namespace = implode('\\', $parts);

        $classGenerator = new Generator\ClassGenerator();
        $classGenerator->setNamespaceName($namespace)
            ->addUse('Core42\Db\TableGateway\AbstractTableGateway')
            ->setName($class)
            ->setExtendedClass('Core42\Db\TableGateway\AbstractTableGateway');

        $property = new Generator\PropertyGenerator('table');
        $property->setDefaultValue($this->tableName)
            ->setDocBlock(
                new Generator\DocBlockGenerator(
                    null,
                    null,
                    [new Generator\DocBlock\Tag\GenericTag('var', 'string')]
                )
            )
            ->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);
        $classGenerator->addPropertyFromGenerator($property);

        $pkc = null;
        foreach ($metadata->getConstraints($this->tableName) as $constraint) {
            if ($constraint->getType() == 'PRIMARY KEY') {
                $pkc = $constraint;
                break;
            }
        }
        if ($pkc) {
            $primaryKey = $pkc->getColumns();

            $property = new Generator\PropertyGenerator('primaryKey');
            $property->setDefaultValue(
                $primaryKey,
                Generator\ValueGenerator::TYPE_ARRAY_SHORT,
                Generator\ValueGenerator::OUTPUT_SINGLE_LINE
            )
                ->setDocBlock(
                    new Generator\DocBlockGenerator(
                        null,
                        null,
                        [new Generator\DocBlock\Tag\GenericTag('var', 'array')]
                    )
                )
                ->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);
            $classGenerator->addPropertyFromGenerator($property);
        }

        $databaseTypeMap = [];
        $columns = $metadata->getColumns($this->tableName);

        foreach ($columns as $column) {
            if ($column->getDataType() == 'enum'
                && in_array($column->getErrata('permitted_values'), [['true', 'false'], ['false', 'true']])
            ) {
                $databaseTypeMap[$column->getName()] = 'Boolean';
            } elseif (in_array($column->getDataType(), ['date'])) {
                $databaseTypeMap[$column->getName()] = 'Date';
            } elseif (in_array($column->getDataType(), ['datetime', 'timestamp'])) {
                $databaseTypeMap[$column->getName()] = 'DateTime';
            } elseif (in_array($column->getDataType(), ['decimal', 'numeric', 'float', 'double'])) {
                $databaseTypeMap[$column->getName()] = 'Float';
            } elseif (in_array($column->getDataType(), ['smallint', 'mediumint', 'int', 'bigint'])) {
                $databaseTypeMap[$column->getName()] = 'Integer';
            } else {
                $databaseTypeMap[$column->getName()] = 'String';
            }
        }

        $property = new Generator\PropertyGenerator('databaseTypeMap');
        $property->setDefaultValue(
            $databaseTypeMap,
            Generator\ValueGenerator::TYPE_ARRAY_SHORT,
            Generator\ValueGenerator::OUTPUT_MULTIPLE_LINE
        )
            ->setDocBlock(
                new Generator\DocBlockGenerator(
                    null,
                    null,
                    [new Generator\DocBlock\Tag\GenericTag('var', 'array')]
                )
            )
            ->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);
        $classGenerator->addPropertyFromGenerator($property);


        $property = new Generator\PropertyGenerator('modelPrototype');
        $property->setDefaultValue($this->model)
            ->setDocBlock(
                new Generator\DocBlockGenerator(
                    null,
                    null,
                    [new Generator\DocBlock\Tag\GenericTag('var', 'string')]
                )
            )
            ->setFlags(Generator\PropertyGenerator::FLAG_PROTECTED);
        $classGenerator->addPropertyFromGenerator($property);

        $filename = $this->directory . $class . '.php';
        file_put_contents($filename, "<?php\n" . $classGenerator->generate());
    }
}
