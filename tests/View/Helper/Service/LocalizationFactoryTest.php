<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 12:08
 */

namespace Core42Test\View\Helper\Service;


use Core42\I18n\Localization\Localization;
use Core42\View\Helper\Proxy;
use Core42\View\Helper\Service\LocalizationFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class LocalizationFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new LocalizationFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(Localization::class, $this->prophesize(Localization::class));

        $proxy = $factory($serviceManager, 'localization', []);
        $this->assertInstanceOf(Proxy::class, $proxy);
    }
}
