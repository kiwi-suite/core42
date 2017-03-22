<?php
namespace Core42Test\Cache\Driver;

use Core42\Cache\Driver\Service\CompositeFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Stash\Driver\Composite;
use Stash\Exception\RuntimeException;

class CompositeFactoryTest extends TestCase
{
    public function testException()
    {
        $this->expectException(RuntimeException::class);
        $factory = new CompositeFactory();
        $factory($this->prophesize(ContainerInterface::class)->reveal(), Composite::class);
    }

    public function textInvoke()
    {
        $factory = new CompositeFactory();
        $response = $factory($this->prophesize(ContainerInterface::class)->reveal(), Composite::class, ['driver' => 'filesystem']);
        $this->assertInstanceOf(Composite::class, $response);
    }
}
