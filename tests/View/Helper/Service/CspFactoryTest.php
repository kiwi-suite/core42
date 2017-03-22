<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 12:09
 */

namespace Core42Test\View\Helper\Service;


use Core42\Security\Csp\Csp;
use Core42\View\Helper\Proxy;
use Core42\View\Helper\Service\CspFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class CspFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new CspFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(Csp::class, $this->prophesize(Csp::class));

        $proxy = $factory($serviceManager, 'csp', []);
        $this->assertInstanceOf(Proxy::class, $proxy);
    }
}
