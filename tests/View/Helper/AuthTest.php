<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 09:53
 */

namespace Core42Test\View\Helper;


use Core42\Authentication\AuthenticationService;
use Core42\View\Helper\Auth;
use PHPUnit\Framework\TestCase;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\NonPersistent;
use Zend\ServiceManager\ServiceManager;
use stdClass;

class AuthTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager();

        $authService = new AuthenticationService(new NonPersistent());
        $authService->setAuthResult(new Result(Result::SUCCESS, 1));
        $authService->authenticate();
        $this->serviceManager->setService("auth", $authService);

        $authService = new AuthenticationService(new NonPersistent());
        $this->serviceManager->setService("non-auth", $authService);

        $this->serviceManager->setService("other-class", new stdClass());
    }

    public function testInvoke()
    {
        $auth = new Auth($this->serviceManager);
        $this->assertSame($auth, $auth("auth"));
        $this->assertSame($auth, $auth("doesnt-exist"));
    }

    public function testHasIdentity()
    {
        $auth = new Auth($this->serviceManager);
        $this->assertTrue($auth("auth")->hasIdentity());
        $this->assertFalse($auth("non-auth")->hasIdentity());
    }

    public function testGetIdentity()
    {
        $auth = new Auth($this->serviceManager);
        $this->assertNull($auth("non-auth")->getIdentity());
        $this->assertSame(1, $auth("auth")->getIdentity());
    }

    public function testEmptyException()
    {
        $this->expectException(\Exception::class);

        $auth = new Auth($this->serviceManager);
        $auth("")->hasIdentity();
    }

    public function testNonExistException()
    {
        $this->expectException(\Exception::class);

        $auth = new Auth($this->serviceManager);
        $auth("nothing")->hasIdentity();
    }

    public function testInvalidClassException()
    {
        $this->expectException(\Exception::class);

        $auth = new Auth($this->serviceManager);
        $auth("other-class")->hasIdentity();
    }
}
