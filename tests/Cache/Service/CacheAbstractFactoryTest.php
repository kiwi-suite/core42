<?php
namespace Core42Test\Cache\Service;

use Core42\Cache\Service\CacheAbstractFactory;
use Core42\Cache\Service\DriverAbstractFactory;
use Core42\Cache\Service\DriverPluginManager;
use PHPUnit\Framework\TestCase;
use Stash\Pool;
use Zend\ServiceManager\ServiceManager;

class CacheAbstractFactoryTest extends TestCase
{
    protected $services;

    protected static $config;

    public static function setUpBeforeClass()
    {
        $config = [];
        if (file_exists('../config/caches.config.php')) {
            $config = require '../config/caches.config.php';

            if (!empty($config['cache']) && array_key_exists('caches', $config['cache'])) {
                $config['cache']['caches']['test'] = [
                    'driver' => 'ephemeral',
                    'namespace' => 'test',
                ];

                unset($config['cache']['caches']['test1']);
            }
        }

        self::$config = $config;
    }

    public function setUp()
    {
        $this->services = new ServiceManager();

        $driverPluginManager = new DriverPluginManager($this->services);
        $driverPluginManager->addAbstractFactory(new DriverAbstractFactory());

        $this->services->setService('config', self::$config);
        $this->services->setService(DriverPluginManager::class, $driverPluginManager);
    }

    public function testCanCreate()
    {
        $cacheAbstractFactory = new CacheAbstractFactory();

        $this->assertTrue($cacheAbstractFactory->canCreate($this->services, 'test'));
        $this->assertFalse($cacheAbstractFactory->canCreate($this->services, 'test1'));
    }


    public function testInvoke()
    {
        $cacheAbstractFactory = new CacheAbstractFactory();

        $this->assertInstanceOf(Pool::class, $cacheAbstractFactory($this->services, 'test'));
    }
}
