<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 12:12
 */

namespace Core42Test\View\Helper\Service;


use Core42\View\Helper\Auth;
use Core42\View\Helper\Service\AuthFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class AuthFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new AuthFactory();

        $serviceManager = new ServiceManager();

        $class = $factory($serviceManager, Auth::class, []);
        $this->assertInstanceOf(Auth::class, $class);
    }
}
