<?php
namespace Core42\Db\DataConverter;

use Core42\ServiceManager\ServiceManagerStaticAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Core42\Db\DataConverter\Adapter\AdapterInterface;

class DataConverter implements ServiceManagerStaticAwareInterface
{
    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    /**
     *
     * @var AdapterInterface
     */
    private $adapter;

    public function __construct()
    {
        $className = 'Core42\Db\DataConverter\Adapter\\' . $this->getServiceManager()->get('Db\Master')->getPlatform()->getName();
        $this->adapter = new $className;
    }

    /**
     *
     * @return \Core42\Db\DataConverter\DataConverter
     */
    public static function createInstance()
    {
        return new self;
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

    public function convertDatetimeToDb(\DateTime $datetime)
    {
        return $this->adapter->convertDatetimeToDb($datetime);
    }

    public function convertDatetimeToLocal($value)
    {
        return $this->adapter->convertDatetimeToLocal($value);
    }

    public function convertBooleanToDb($boolean)
    {
        return $this->adapter->convertBooleanToDb($boolean);
    }

    public function convertBooleanToLocal($value)
    {
        return $this->adapter->convertBooleanToLocal($value);
    }
}
