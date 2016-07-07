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
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Code\Generator;
use Zend\Code\Reflection;
use Zend\Db\Adapter;
use Zend\Filter\Word\UnderscoreToCamelCase;

class GenerateModelCommand extends AbstractCommand
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
    private $className;

    /**
     * @type string
     */
    private $directory;

    /**
     * @type string
     */
    private $tableName;

    /**
     * @var bool
     */
    private $generateSetterGetter = false;

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
     * @param boolean $generateSetterGetter
     * @return $this
     */
    public function setGenerateSetterGetter($generateSetterGetter)
    {
        $this->generateSetterGetter = $generateSetterGetter;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!isset($this->className)) {
            $this->addError('className', "className parameter not set");
            return;
        }

        if (!isset($this->directory)) {
            $this->addError('directory', "directory parameter not set");
            return;
        }

        if (empty($this->tableName)) {
            $this->addError('tableName', "tableName parameter not set");
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

        $this->directory = rtrim($this->directory, '/') . '/';
    }

    /**
     *
     */
    protected function execute()
    {
        $metadata = Factory::createSourceFromAdapter($this->adapter);
        $columns = $metadata->getColumns($this->tableName);

        $parts =  explode("\\", $this->className);
        $class = array_pop($parts);
        $namespace = implode("\\", $parts);

        $modelClass = new Generator\ClassGenerator();
        $modelClass->setNamespaceName($namespace)
            ->addUse('Core42\Model\AbstractModel')
            ->setName($class)
            ->setExtendedClass('Core42\Model\AbstractModel');

        $filter = new UnderscoreToCamelCase();

        $tags = [];
        $properties = [];
        $methods = [];
        foreach ($columns as $column) {
            /* @type \Zend\Db\Metadata\Object\ColumnObject $column */

            $method = ucfirst($filter->filter($column->getName()));

            $properties[] = $column->getName();

            $type = $this->getPropertyTypeByColumnObject($column);

            if ($this->generateSetterGetter === false) {
                $setterMethodDocBlock = new Generator\DocBlock\Tag\MethodTag(
                    "set".$method,
                    [$class],
                    "set".$method."(".$type." \$".$column->getName().")"
                );

                $getterMethodDocBlock = new Generator\DocBlock\Tag\MethodTag(
                    "get".$method,
                    [$type],
                    "get".$method."()"
                );

                $tags[] = $setterMethodDocBlock;
                $tags[] = $getterMethodDocBlock;
            } else {
                $docBlockParam = new Generator\DocBlock\Tag\ParamTag();
                $docBlockParam->setVariableName($column->getName());
                $docBlockParam->setTypes($this->getPropertyTypeByColumnObject($column));

                $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
                $docBlockReturn->setTypes('$this');

                $methods[] = new Generator\MethodGenerator(
                    'set'.$method,
                    [
                        new Generator\ParameterGenerator(
                            $column->getName(),
                            null,
                            null
                        )
                    ],
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    implode("\n", [
                        '$this->set(\''.$column->getName().'\', $'.$column->getName().');',
                        'return $this;'
                    ]),
                    new Generator\DocBlockGenerator(
                        null,
                        null,
                        [
                            $docBlockParam,
                            $docBlockReturn
                        ]
                    )
                );

                //getter
                $docBlockReturn = new Generator\DocBlock\Tag\ReturnTag();
                $docBlockReturn->setTypes($this->getPropertyTypeByColumnObject($column));

                $methods[] = new Generator\MethodGenerator(
                    'get'.$method,
                    [],
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    "return \$this->get('".$column->getName()."');",
                    new Generator\DocBlockGenerator(
                        null,
                        null,
                        [
                            $docBlockReturn
                        ]
                    )
                );
            }
        }

        if (!empty($tags)) {
            $docBlock = new Generator\DocBlockGenerator();
            $docBlock->setTags($tags);

            $modelClass->setDocBlock($docBlock);
        }

        $propertyGenerator = new Generator\PropertyGenerator("properties");
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
        $modelClass->addPropertyFromGenerator($propertyGenerator);

        $modelClass->addMethods($methods);

        $filename = $this->directory . $class . '.php';
        file_put_contents($filename, "<?php\n" . $modelClass->generate());
    }

    /**
     * @param \Zend\Db\Metadata\Object\ColumnObject $column
     * @return string
     */
    protected function getPropertyTypeByColumnObject(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        switch($column->getDataType()){
            case "boolean":
            case "bool":
                return "boolean";
            case "enum":
                $check = [["true", "false"], ["false", "true"]];
                if (in_array($column->getErrata("permitted_values"), $check)) {
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
