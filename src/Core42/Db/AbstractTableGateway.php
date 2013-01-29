<?php
namespace Core42\Db;

use Zend\Stdlib\Hydrator\ClassMethods;

use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Application\Registry;
use Core42\Db\ResultSet\ResultSet;
abstract class AbstractTableGateway extends ZendAbstractTableGateway
{
    /**
     *
     * @var string
     */
    protected $rowGatewayDefinition = 'Zend\Db\RowGateway\RowGateway';
    
    
    /**
     *
     * @var array
     */
    private static $instance = array();
    
    protected function __construct()
    {
    	$this->table = $this->getCurrentTable();
    	
        $this->adapter = Registry::getDbAdapter();
        $this->resultSetPrototype = new ResultSet(new ClassMethods(), $this->getObjectPrototype());
        
        $className = $this->rowGatewayDefinition;
        $rowGateway = new $className($this->getPrimaryKey(), $this->table, $this->adapter);
        $this->resultSetPrototype->setArrayObjectPrototype($rowGateway);
        
        $this->initialize();
    }
    
    /**
     * @return string
     */
    abstract protected function getTableName();
    
    /**
     * @return array
     */
    abstract protected function getPrimaryKey();
    
    /**
     * @return \Object
     */
    abstract protected function getObjectPrototype();
    
    /**
     *
     * @return mixed:
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!array_key_exists($className, self::$instance)) {
            self::$instance[$className] = new $className;
        }
    
        return self::$instance[$className];
    }
}
