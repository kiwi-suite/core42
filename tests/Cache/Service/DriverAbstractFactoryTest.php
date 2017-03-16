<?php
namespace Core42Test\Cache\Service;

use Core42\Cache\Service\DriverAbstractFactory;
use Core42\Cache\Service\DriverPluginManager;
use PHPUnit\Framework\TestCase;
use Stash\Driver\Ephemeral;
use Stash\Pool;
use Zend\ServiceManager\ServiceManager;

class DriverAbstractFactoryTest extends TestCase
{
    protected $services;

    protected static $config;

    public static function setUpBeforeClass()
    {
        $config = [];
        if (file_exists('../config/caches.config.php')) {
            $config = require '../config/caches.config.php';
        }

        if (!isset($config['cache']['drivers'])) {
            $config['cache']['drivers'] = [
                'ephemeral' => [
                    'driver' => Ephemeral::class,
                    'options' => [],
                ],
            ];
        }

        self::$config = $config;

        unset($config['cache']['drivers']['test']);
    }

    public function setUp()
    {
        $this->services = new ServiceManager();
        $this->services->setService('config', self::$config);

        $driverPluginManager = new DriverPluginManager($this->services);
        $this->services->setService(DriverPluginManager::class, $driverPluginManager);
    }

    public function testCanCreate()
    {
        $driverAbstractFactors = new DriverAbstractFactory();

        $this->assertTrue($driverAbstractFactors->canCreate($this->services, 'ephemeral'));
        $this->assertFalse($driverAbstractFactors->canCreate($this->services, 'test'));
    }


    public function testInvoke()
    {
        $driverAbstractFactors = new DriverAbstractFactory();

        $this->assertInstanceOf(Ephemeral::class, $driverAbstractFactors($this->services, 'ephemeral'));
    }
}
