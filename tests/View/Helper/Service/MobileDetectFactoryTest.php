<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 12:03
 */

namespace Core42Test\View\Helper\Service;


use Core42\View\Helper\Proxy;
use Core42\View\Helper\Service\MobileDetectFactory;
use Detection\MobileDetect;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class MobileDetectFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new MobileDetectFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(MobileDetect::class, $this->prophesize(MobileDetect::class));

        $proxy = $factory($serviceManager, 'mobileDetect', []);
        $this->assertInstanceOf(Proxy::class, $proxy);
    }
}
