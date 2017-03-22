<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 13:17
 */

namespace Core42Test\Selector\Service;


use Core42\Selector\Service\SelectorPluginManager;
use Core42Test\Selector\TestSelector;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceManager;


class SelectorPluginManagerTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager();
    }

    public function testGet()
    {
        $selectorPluginManager = new SelectorPluginManager($this->serviceManager, []);
        $this->assertInstanceOf(
              TestSelector::class,
            $selectorPluginManager->get(TestSelector::class)
        );
    }

    public function testGetException()
    {
        $this->expectException(ServiceNotFoundException::class);

        $selectorPluginManager = new SelectorPluginManager($this->serviceManager, []);
        $selectorPluginManager->get("test");
    }
}
