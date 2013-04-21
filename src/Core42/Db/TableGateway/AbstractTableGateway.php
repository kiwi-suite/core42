<?php
namespace Core42\Db\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Db\ResultSet\ResultSet;
use Core42\Model\Model;
use Core42\Hydrator\ModelHydrator;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;

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
     * @var array
     */
    protected $primaryKey = array();
    
    /**
     * 
     * @var string|Model
     */
    protected $modelPrototype = null;
    
    
    /**
     *
     * @var array
     */
    private static $instance = array();
    
    /**
     * 
     * @var ServiceManager
     */
    private static $serviceManager = null;
    
    protected function __construct()
    {
        $this->adapter = $this->getServiceManager()->get("db_master");
        $className = $this->rowGatewayDefinition;
        $rowGateway = new $className($this->primaryKey, $this->table, $this->modelPrototype, $this->adapter);

        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $rowGateway);
        
        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new MasterSlaveFeature($this->getServiceManager()->get("db_slave")));
        
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
    
    public function insert($set)
    {
        if ($set instanceof Model) {
            $hydrator = new ModelHydrator();
            $set = $hydrator->extract($set);
        }
        
        return parent::insert($set);
    }
}
