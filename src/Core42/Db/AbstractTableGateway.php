<?php
namespace Core42\Db;

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
    protected $primaryKey = array('id');
    
    /**
     * 
     * @var string
     */
    protected $table = '';
    
    /**
     *
     * @var array
     */
    private static $instance = array();
    
    protected function __construct()
    {
        $this->adapter = Registry::getDbAdapter();
        $this->resultSetPrototype = new ResultSet();
        
        $className = $this->rowGatewayDefinition;
        $rowGateway = new $className($this->primaryKey, $this->table, $this->adapter);
        $this->resultSetPrototype->setArrayObjectPrototype($rowGateway);
        
        $this->initialize();
    }
    
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
