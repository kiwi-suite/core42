<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 09:14
 */

namespace Core42Test\View\Model;


use Core42\View\Model\JsonModel;
use PHPUnit\Framework\TestCase;


class JsonModelTest extends TestCase
{

    public function testSetJsonString()
    {
        $jsonModel = new JsonModel(["test" => "test"]);
        $this->assertSame($jsonModel, $jsonModel->setJsonString('{"new":"new"}'));
    }

    public function testSerialize()
    {
        $jsonModel = new JsonModel(["test" => "test"]);
        $this->assertSame('{"test":"test"}', $jsonModel->serialize());

        $jsonModel = new JsonModel(["test" => "test"]);
        $jsonModel->setJsonString('{"new":"new"}');
        $this->assertSame('{"new":"new"}', $jsonModel->serialize());
    }
}
