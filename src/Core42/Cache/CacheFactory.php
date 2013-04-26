<?php
namespace Core42\Cache;

use Core42\ServiceManager\ServiceManagerStaticAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Cache\StorageFactory;

class CacheFactory implements ServiceManagerStaticAwareInterface
{
    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    /**
     *
     * @var array
     */
    private static $_instances = array();

    /**
     *
     * @param string $name
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public static function get($name = "default")
    {
        if (!isset(self::$_instances[$name])) {
            self::$_instances[$name] = self::buildCache($name);
        }
        return self::$_instances[$name];
    }

    /**
     *
     * @param string $name
     * @throws \Exception
     * @return \Zend\Cache\Storage\StorageInterface
     */
    private static function buildCache($name)
    {
        $config = self::$serviceManager->get("Config");
        if (!isset($config["cache"][$name])) {
            throw new \Exception("cache {$name} not found in config");
        }

        return StorageFactory::factory($config["cache"][$name]);
    }

    /**
     *
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }

}
