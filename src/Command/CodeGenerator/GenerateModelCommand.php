<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */


namespace Core42\Command\CodeGenerator;

use Core42\Command\AbstractCommand;
use Zend\Code\Reflection\FileReflection;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Code\Generator;
use Zend\Db\Adapter;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * Class GenerateModelCommand
 * @package Core42\Command\CodeGenerator
 */
class GenerateModelCommand extends AbstractCommand
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
     * @var bool
     */
    private $generateSetterGetter = false;

    /**
     * @var bool
     */
    private $overwrite = false;

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
     * @param bool $generateSetterGetter
     * @return $this
     */
    public function setGenerateSetterGetter($generateSetterGetter)
    {
        $this->generateSetterGetter = $generateSetterGetter;

        return $this;
    }

    /**
     * @param bool $overwrite
     * @return $this
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!isset($this->className)) {
            $this->addError('className', 'className parameter not set');

            return;
        }

        if (!isset($this->directory)) {
            $this->addError('directory', 'directory parameter not set');

            return;
        }

        if (empty($this->tableName)) {
            $this->addError('tableName', 'tableName parameter not set');

            return;
        }

        if (!\is_dir($this->directory)) {
            $this->addError('directory', "directory '" . $this->directory . "' doesn't exist");

            return;
        }

        if (!\is_writable($this->directory)) {
            $this->addError('directory', "directory '" . $this->directory . "' isn't writeable");

            return;
        }

        try {
            $this->adapter = $this->getServiceManager()->get($this->adapterName);
        } catch (ServiceNotFoundException $e) {
            $this->addError('adapter', "adapter '" . $this->adapterName . "' not found");
        }

        $this->directory = \rtrim($this->directory, '/') . '/';
    }

    /**
     *
     */
    protected function execute()
    {
        $metadata = Factory::createSourceFromAdapter($this->adapter);
        $columns = $metadata->getColumns($this->tableName);

        $parts = \explode('\\', $this->className);
        $class = \array_pop($parts);
        $namespace = \implode('\\', $parts);

        $classGenerator = new Generator\ClassGenerator();
        $classGenerator->setNamespaceName($namespace)
            ->addUse('Core42\Model\AbstractModel')
            ->setName($class)
            ->setExtendedClass('Core42\Model\AbstractModel');

        $filename = $this->directory . $class . '.php';
        if (\file_exists($filename) && !$this->overwrite) {
            $this->readExistingModel($filename, $classGenerator);
        }

        $filter = new UnderscoreToCamelCase();

        $hasDate = false;
        $hasDateTime = false;
        $tags = [];
        $properties = [];
        $methods = [];
        foreach ($columns as $column) {
            /* @type \Zend\Db\Metadata\Object\ColumnObject $column */

            $method = \ucfirst($filter->filter($column->getName()));

            $properties[] = $column->getName();

            $type = $this->getPropertyTypeByColumnObject($column, $hasDate, $hasDateTime);

            if ($this->generateSetterGetter === false) {
                $setterMethodDocBlock = new Generator\DocBlock\Tag\MethodTag(
                    null,
                    [$class],
                    'set' . $method . '(' . $type . ' $' . $column->getName() . ')'
                );

                $getterMethodDocBlock = new Generator\DocBlock\Tag\MethodTag(
                    null,
                    [$type],
                    'get' . $method . '()'
                );

                $tags[] = $setterMethodDocBlock;
                $tags[] = $getterMethodDocBlock;
            } else {
                $docBlockParam = new Generator\DocBlock\Tag\ParamTag();
                $docBlockParam->setVariableName($column->getName());
                $docBlockParam->setTypes($type);

                $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
                $docBlockReturn->setTypes('$this');

                $methods[] = new Generator\MethodGenerator(
                    'set' . $method,
                    [
                        new Generator\ParameterGenerator(
                            $column->getName(),
                            null,
                            null
                        ),
                    ],
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    \implode("\n", [
                        '$this->set(\'' . $column->getName() . '\', $' . $column->getName() . ');',
                        'return $this;',
                    ]),
                    new Generator\DocBlockGenerator(
                        null,
                        null,
                        [
                            $docBlockParam,
                            $docBlockReturn,
                        ]
                    )
                );

                //getter
                $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
                $docBlockReturn->setTypes($type);

                $methods[] = new Generator\MethodGenerator(
                    'get' . $method,
                    [],
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    "return \$this->get('" . $column->getName() . "');",
                    new Generator\DocBlockGenerator(
                        null,
                        null,
                        [
                            $docBlockReturn,
                        ]
                    )
                );
            }
        }

        if ($hasDate) {
            $classGenerator->addUse('Core42\Stdlib\Date');
        }
        if ($hasDateTime) {
            $classGenerator->addUse('Core42\Stdlib\DateTime');
        }

        if (!empty($tags)) {
            $docBlock = new Generator\DocBlockGenerator();
            $docBlock->setTags($tags);

            $classGenerator->setDocBlock($docBlock);
        }

        $propertyGenerator = new Generator\PropertyGenerator('properties');
        $propertyGenerator->setVisibility(Generator\PropertyGenerator::VISIBILITY_PROTECTED);
        $propertyGenerator->setDefaultValue(
            $properties,
            Generator\ValueGenerator::TYPE_ARRAY_SHORT,
            Generator\ValueGenerator::OUTPUT_MULTIPLE_LINE
        );
        $propertyGenerator->setDocBlock(new Generator\DocBlockGenerator(
            null,
            null,
            [new Generator\DocBlock\Tag\GenericTag('var', 'array')]
        ));
        $classGenerator->addPropertyFromGenerator($propertyGenerator);

        $classGenerator->addMethods($methods);

        \file_put_contents($filename, "<?php\n" . $classGenerator->generate());
    }

    /**
     * @param string $filename
     * @param Generator\ClassGenerator $classGenerator
     */
    protected function readExistingModel($filename, Generator\ClassGenerator $classGenerator)
    {
        $file = new FileReflection($filename, true);
        $class = $file->getClass();

        $filter = new UnderscoreToCamelCase();

        $constants = $class->getConstants();
        if (!empty($constants)) {
            foreach ($constants as $name => $value) {
                $classGenerator->addConstant($name, $value);
            }
        }

        $tmpMethods = $class->getMethods();
        $methods = [];
        foreach ($tmpMethods as $method) {
            if ($method->class == $class->getName()) {
                $methods[] = $method;
            }
        }
        unset($tmpMethods);

        $properties = $class->getDefaultProperties();
        foreach ($properties['properties'] as $property) {
            $methodName = \ucfirst($filter->filter($property));

            foreach ($methods as $key => $method) {
                if ($method->getName() == ('set' . $methodName)) {
                    unset($methods[$key]);
                    continue;
                }
                if ($method->getName() == ('get' . $methodName)) {
                    unset($methods[$key]);
                    continue;
                }
            }
        }

        if (!empty($methods)) {
            foreach ($methods as $reflection) {
                $method =  Generator\MethodGenerator::fromReflection($reflection);
                $classGenerator->addMethodFromGenerator($method);
            }
        }
    }

    /**
     * @param \Zend\Db\Metadata\Object\ColumnObject $column
     * @param bool $hasDate
     * @param bool $hasDateTime
     * @return string
     */
    protected function getPropertyTypeByColumnObject(\Zend\Db\Metadata\Object\ColumnObject $column, &$hasDate, &$hasDateTime)
    {
        switch ($column->getDataType()) {
            case 'boolean':
            case 'bool':
                return 'boolean';
            case 'enum':
                $check = [['true', 'false'], ['false', 'true']];
                if (\in_array($column->getErrata('permitted_values'), $check)) {
                    return 'boolean';
                }

                return 'string';
            case 'decimal':
            case 'numeric':
            case 'float':
            case 'double':
                return 'float';
            case 'int':
            case 'integer':
            case 'tinyint':
            case 'mediumint':
            case 'bigint':
                return 'int';
            case 'date':
                $hasDate = true;
                return 'Date';
            case 'datetime':
            case 'timestamp':
                $hasDateTime = true;
                return 'DateTime';
            case 'varchar':
            case 'char':
            case 'text':
            default:
                return 'string';
        }
    }
}
