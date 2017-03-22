<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 11:37
 */

namespace Core42Test\View\Helper\Service;


use Cocur\Slugify\Slugify;
use Core42\View\Helper\Proxy;
use Core42\View\Helper\Service\SlugifyFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class SlugifyFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $slugifyFactory = new SlugifyFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(Slugify::class, new Slugify());

        $proxy = $slugifyFactory($serviceManager, Slugify::class, []);
        $this->assertInstanceOf(Proxy::class, $proxy);
        $this->assertSame("test", $proxy->slugify("test"));
    }
}
