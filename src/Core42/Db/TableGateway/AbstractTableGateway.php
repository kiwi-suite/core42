<?php
namespace Core42\Db\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Core42\Application\Registry;
use Core42\Db\ResultSet\ResultSet;

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
    
    protected function __construct()
    {
        $this->adapter = Registry::getDbAdapter();
        
        $className = $this->rowGatewayDefinition;
        $rowGateway = new $className($this->primaryKey, $this->table, $this->modelPrototype, $this->adapter);

        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $rowGateway);
        
        $this->initialize();
    }
    
    /**
     *
     * @return AbstractTableGateway:
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
