<?php
namespace Core42Test\Cache\Service;

use Core42\Cache\Service\DriverPluginManager;
use Core42\Cache\Service\DriverPluginManagerFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class DriverPluginManagerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new DriverPluginManagerFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), DriverPluginManager::class);
        $this->assertInstanceOf(DriverPluginManager::class, $response);
    }
}
