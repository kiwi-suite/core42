<?php
namespace Core42Test\Cache\Driver;

use Core42\Cache\Driver\Service\BlackHoleFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Stash\Driver\BlackHole;

class BlackHoleFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new BlackHoleFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), BlackHole::class);
        $this->assertInstanceOf(BlackHole::class, $response);
    }
}
