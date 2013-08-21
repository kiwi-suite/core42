<?php
namespace Core42\Db\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Hydrator\ModelHydrator;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;
use Core42\Model\AbstractModel;
use Zend\Db\Metadata\Metadata;
use Zend\Db\TableGateway\Feature\MetadataFeature;
use Core42\Db\TableGateway\Feature\HydratorFeature;
use Core42\Db\TableGateway\Feature\RowGatewayFeature;

abstract class AbstractTableGateway extends ZendAbstractTableGateway
{
    /**
     *
     * @var string
     */
    protected $rowGatewayDefinition = '\Core42\Db\RowGateway\RowGateway';

    /**
     *
     * @var string
     */
    protected $table = '';

    /**
     *
     * @var string|AbstractModel
     */
    protected $modelPrototype = null;


    /**
     *
     * @var array
     */
    private static $instance = array();

    /**
     *
     * @var ModelHydrator
     */
    protected $hydrator;

    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    protected function __construct()
    {
        $this->adapter = $this->getServiceManager()->get("db_slave");

        $metadata = new Metadata($this->adapter);

        if ($this->hydrator === null) {
            $this->hydrator = new ModelHydrator();
        }

        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new MasterSlaveFeature($this->getServiceManager()->get("db_slave")));
        $this->featureSet->addFeature(new MetadataFeature($metadata));
        $this->featureSet->addFeature(new RowGatewayFeature($this->rowGatewayDefinition, $this->modelPrototype, $this->hydrator));
        $this->featureSet->addFeature(new HydratorFeature($metadata));

        $this->initialize();
    }

    /**
     *
     * @param ServiceManager $manager
     */
    public static function setServiceManager(ServiceManager $manager)
    {
        self::$serviceManager = $manager;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     *
     * @return \Zend\Db\TableGateway\AbstractTableGateway
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!array_key_exists($className, self::$instance)) {
            self::$instance[$className] = new $className;
        }

        return self::$instance[$className];
    }

    /**
     * @return \Core42\Hydrator\ModelHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    public function insert($set)
    {
        if ($set instanceof AbstractModel) {
            $set = $this->hydrator->extract($set);
        }

        return parent::insert($set);
    }
}
