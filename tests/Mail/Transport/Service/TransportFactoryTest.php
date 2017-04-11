<?php
namespace Core42Test\Mail\Transport\Service;

use Core42\Mail\Transport\Service\TransportFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

class TransportFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService("config", [
            'mail' => [
                'transport' => [
                    'type' => 'null',
                    'options' => [],
                ],
            ],
        ]);

        $transportFactory = new TransportFactory();
        $this->assertInstanceOf(\Swift_NullTransport::class, $transportFactory($serviceManager, 'Core42\Mail\Transport'));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);

        $serviceManager = new ServiceManager();
        $serviceManager->setService("config", []);

        $transportFactory = new TransportFactory();
        $transportFactory($serviceManager, 'Core42\Mail\Transport');
    }
}
