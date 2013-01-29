<?php
namespace Core42\Application;

use Zend\ServiceManager\ServiceLocatorInterface;

class Registry
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    private static $serviceLocator;

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public static function setServiceManager(ServiceLocatorInterface $serviceLocator)
    {
        self::$serviceLocator = $serviceLocator;
    }

    /**
     *
     * @throws \Exception
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public static function getServiceManager()
    {
        if (self::$serviceLocator === null) {
            throw new \Exception("no servicelocator");
        }

        return self::$serviceLocator;
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        return self::getServiceManager()->get($name);
    }
    
    /**
     *
     * @return \Zend\Db\Adapter\Adapter
     */
    public static function getDbAdapter($dbAdapter = 'db_master')
    {
        return self::get($dbAdapter);
    }
}
