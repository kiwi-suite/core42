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
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Code\Generator;
use Zend\Code\Reflection;
use Zend\Db\Adapter;

class GenerateTableGatewayCommand extends AbstractCommand
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
     * @var string
     */
    private $model;


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
            $this->addError('className', "className parameter not set");
            return;
        }

        if (empty($this->directory)) {
            $this->addError('directory', "directory parameter not set");
            return;
        }

        if (empty($this->tableName)) {
            $this->addError('tableName', "tableName parameter not set");
            return;
        }

        if (empty($this->model)) {
            $this->addError('model', "model parameter not set");
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
        $metadata->getTable($this->tableName);

        $parts =  explode("\\", $this->className);
        $class = array_pop($parts);
        $namespace = implode("\\", $parts);

        $classGenerator = new Generator\ClassGenerator();
        $classGenerator->setNamespaceName($namespace)
            ->addUse('Core42\Db\TableGateway\AbstractTableGateway')
            ->setName($class)
            ->setExtendedClass('Core42\Db\TableGateway\AbstractTableGateway');

        $property = new Generator\PropertyGenerator("table");
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

            $property = new Generator\PropertyGenerator("primaryKey");
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

        /** @var HydratorStrategyPluginManager $hydratorStrategyManager */
        $hydratorStrategyManager = $this->getServiceManager()->get(HydratorStrategyPluginManager::class);
        $aliases = $hydratorStrategyManager->getServiceAliases();
        foreach ($columns as $column) {
            foreach ($aliases as $alias) {
                $strategy = $hydratorStrategyManager->get($alias);
                if ($strategy->isResponsible($column)) {
                    $serviceName = $strategy->getName();
                    if (empty($serviceName)) {
                        $serviceName = $alias;
                    }

                    if (!$hydratorStrategyManager->has($serviceName)) {
                        $serviceName = $alias;
                    }

                    $databaseTypeMap[$column->getName()] = $serviceName;

                    break;
                }
            }
        }

        $property = new Generator\PropertyGenerator("databaseTypeMap");
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


        $property = new Generator\PropertyGenerator("modelPrototype");
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
        file_put_contents($filename, "<?php\n".$classGenerator->generate());
    }
}
