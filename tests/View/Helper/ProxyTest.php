<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 16/03/2017
 * Time: 21:35
 */

namespace Core42Test\View\Helper;


use Core42\Model\GenericModel;
use Core42\View\Helper\Proxy;
use PHPUnit\Framework\TestCase;


class ProxyTest extends TestCase
{
    /**
     * @var GenericModel
     */
    protected $model;

    public function setUp()
    {
        $this->model = new GenericModel([
            'test1' => 'test1',
            'test2' => 'test2',
        ]);
    }

    public function testCall()
    {
        $proxy = new Proxy($this->model);
        $this->assertEquals(["test1", "test2"], $proxy->getProperties());

        $proxy = new Proxy(null);
        $this->assertNull($proxy->getProperties());
    }

    public function testGet()
    {
        $proxy = new Proxy($this->model);
        $this->assertEquals('test1', $proxy->test1);

        $proxy = new Proxy(null);
        $this->assertNull($proxy->test1);
    }

    public function testSet()
    {
        $proxy = new Proxy($this->model);
        $proxy->test1 = "new";
        $this->assertEquals('new', $proxy->test1);
    }

    public function testIsset()
    {
        $proxy = new Proxy($this->model);
        $this->assertTrue(isset($proxy->test1));

        $proxy = new Proxy(null);
        $this->assertFalse(isset($proxy->test1));
    }
}
