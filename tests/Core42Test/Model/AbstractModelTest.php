<?php
namespace Core42Test\Model;

use Core42\Hydrator\ModelHydrator;

class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrating()
    {
        $dateTime = new \DateTime();
        $testData = array(
            'id'   => 42,
            'date' => $dateTime,
        );

        $testModel = new TestModel();
        $hydrator = new ModelHydrator();


        $this->assertEquals($testData, $hydrator->extract($hydrator->hydrate($testData, $testModel)));
        $this->assertNotEquals(array('id' => 42,
            'date', new \DateTime("2012-12-12 12:12:12")
        ), $hydrator->extract($hydrator->hydrate($testData, $testModel)));
    }

    public function testFilter()
    {
        $testModel = new TestModel();

        $testModel->setId((string) "45");
        $testModel->filter();
        $this->assertSame(45, $testModel->getId());

        $testModel->setId((string) "45");
        $this->assertSame("45", $testModel->getId());
    }

    public function testValidation()
    {
        $dateTime = new \DateTime();
        $testData = array(
            'id'   => 42,
            'date' => $dateTime,
        );
        $testModel = new TestModel();
        $hydrator = new ModelHydrator();

        $hydrator->hydrate($testData, $testModel);
        $this->assertTrue($testModel->isValid());
    }

    public function testMemento()
    {
        $dateTime = new \DateTime();
        $testData = array(
            'id'   => 42,
            'date' => $dateTime,
        );

        $testModel = new TestModel();
        $hydrator = new ModelHydrator();
        $hydrator->hydrate($testData, $testModel);

        $testModel->setId(42);
        $testModel->memento();
        $this->assertEquals(array(), $testModel->diff());
        $this->assertNotEquals(array('id' => 42,
            'date', new \DateTime("2012-12-12 12:12:12")
        ), $testModel->diff());
        $this->assertNotEquals(array('id' => "42",
            'date', $dateTime
        ), $testModel->diff());
    }
}
