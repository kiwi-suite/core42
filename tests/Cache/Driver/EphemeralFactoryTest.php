<?php
namespace Core42Test\Cache\Driver;

use Core42\Cache\Driver\Service\EphemeralFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Stash\Driver\Ephemeral;

class EphemeralFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new EphemeralFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), Ephemeral::class);
        $this->assertInstanceOf(Ephemeral::class, $response);
    }
}
