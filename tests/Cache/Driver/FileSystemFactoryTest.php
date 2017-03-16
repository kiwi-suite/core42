<?php
namespace Core42Test\Cache\Driver;

use Core42\Cache\Driver\Service\FileSystemFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Stash\Driver\FileSystem;

class FileSystemFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new FileSystemFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), FileSystem::class);
        $this->assertInstanceOf(FileSystem::class, $response);
    }
}
