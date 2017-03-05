<?php
namespace Core42Test\Authentication;

use Core42\Authentication\AuthenticationService;
use PHPUnit\Framework\TestCase;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\NonPersistent;
use Zend\Authentication\Storage\StorageInterface;

class AuthenticationServiceTest extends TestCase
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    public function setUp()
    {
        $this->storage = new NonPersistent();
    }

    public function testConstruct()
    {
        $service = new AuthenticationService($this->storage);
        $this->assertEquals($service->getStorage(), $this->storage);

        $service = new AuthenticationService();
        $this->assertNull($service->getStorage());
    }

    public function testAuthenticate()
    {
        $service = new AuthenticationService($this->storage);
        $this->assertInstanceOf(Result::class, $service->authenticate());

        $service = new AuthenticationService($this->storage);
        $result = $service->authenticate();
        $this->assertEquals(Result::FAILURE_UNCATEGORIZED, $result->getCode());

        $service = new AuthenticationService($this->storage);
        $result = new Result(
            Result::SUCCESS,
            1,
            []
        );
        $service->setAuthResult($result);
        $this->assertEquals($result, $service->authenticate());

        $service = new AuthenticationService($this->storage);
        $result = new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            1,
            []
        );
        $service->setAuthResult($result);
        $service->authenticate();
        $this->assertNull($service->getIdentity());

        $service = new AuthenticationService($this->storage);
        $result = new Result(
            Result::SUCCESS,
            1,
            []
        );
        $service->setAuthResult($result);
        $service->authenticate();
        $this->assertEquals(1, $service->getIdentity());

        $service = new AuthenticationService($this->storage);
        $result = new Result(
            Result::SUCCESS,
            1,
            []
        );
        $service->setAuthResult($result);
        $service->authenticate();
        $service->authenticate();
        $this->assertNull($service->getIdentity());
    }
}
