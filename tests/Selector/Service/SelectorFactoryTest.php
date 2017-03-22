<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 09:56
 */

namespace Core42Test\Selector\Service;


use Core42\Selector\Service\SelectorFactory;
use Core42Test\Selector\TestSelector;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class SelectorFactoryTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager();
    }

    public function testInvoke()
    {
        $selectorFactory = new SelectorFactory();

        $this->assertInstanceOf(TestSelector::class, $selectorFactory($this->serviceManager, TestSelector::class));
    }
}
