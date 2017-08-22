<?php
namespace Core42Test\View\Helper;


use Core42\View\Helper\Uuid;
use PHPUnit\Framework\TestCase;


class UuidTest extends TestCase
{

    public function testInvoke()
    {
        $uuid = new Uuid();
        $this->assertEquals($uuid, $uuid());
    }

    public function testUuid1()
    {
        $uuidHelper = new Uuid();

        $uuid = $uuidHelper->uuid1();
        $uuidModel = \Ramsey\Uuid\Uuid::fromString($uuid);
        $this->assertInstanceOf(\Ramsey\Uuid\Uuid::class, $uuidModel);
        $this->assertEquals($uuid, $uuidModel->toString());
    }

    public function testUuid3()
    {
        $uuidHelper = new Uuid();

        $uuid = $uuidHelper->uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_DNS, 'kiwi-suite.com');
        $uuidModel = \Ramsey\Uuid\Uuid::fromString($uuid);
        $this->assertInstanceOf(\Ramsey\Uuid\Uuid::class, $uuidModel);
        $this->assertEquals($uuid, $uuidModel->toString());
    }

    public function testUuid4()
    {
        $uuidHelper = new Uuid();

        $uuid = $uuidHelper->uuid4();
        $uuidModel = \Ramsey\Uuid\Uuid::fromString($uuid);
        $this->assertInstanceOf(\Ramsey\Uuid\Uuid::class, $uuidModel);
        $this->assertEquals($uuid, $uuidModel->toString());
    }

    public function testUuid5()
    {
        $uuidHelper = new Uuid();

        $uuid = $uuidHelper->uuid5(\Ramsey\Uuid\Uuid::NAMESPACE_DNS, 'kiwi-suite.com');
        $uuidModel = \Ramsey\Uuid\Uuid::fromString($uuid);
        $this->assertInstanceOf(\Ramsey\Uuid\Uuid::class, $uuidModel);
        $this->assertEquals($uuid, $uuidModel->toString());
    }
}
