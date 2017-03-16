<?php
namespace Core42Test\Cache\Service;

use Core42\Cache\Service\CachePluginManager;
use Core42\Cache\Service\CachePluginManagerFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class CachePluginManagerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new CachePluginManagerFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), CachePluginManager::class);
        $this->assertInstanceOf(CachePluginManager::class, $response);
    }
}
