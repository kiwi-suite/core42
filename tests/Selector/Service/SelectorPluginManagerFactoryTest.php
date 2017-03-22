<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 13:11
 */

namespace Core42Test\Selector\Service;


use Core42\Selector\Service\SelectorPluginManager;
use Core42\Selector\Service\SelectorPluginManagerFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class SelectorPluginManagerFactoryTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager();
        $this->serviceManager->setService('config', []);
    }

    public function testInvoke()
    {
        $selectorPluginManagerFactory = new SelectorPluginManagerFactory();
        $this->assertInstanceOf(
            SelectorPluginManager::class,
            $selectorPluginManagerFactory($this->serviceManager, SelectorPluginManager::class)
        );
    }
}
