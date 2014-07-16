<?php
namespace Core42Test\Model;

use Core42\Model\DefaultModel;

class DefaultModelTest extends \PHPUnit_Framework_TestCase
{
    private $defaultModel;

    public function setUp()
    {
        $this->defaultModel = new DefaultModel();
    }

    /**
     * @covers Core42\Model\DefaultModel::exchangeArray
     * @covers Core42\Model\DefaultModel::hydrate
     * @covers Core42\Model\DefaultModel::extract
     */
    public function testExchangeExtractHydrateArray()
    {
        $array = array(
            'int'           => 1,
            'string'        => 'test',
            'datetime'      => new \DateTime(),
            'array'         => array(1,2,3),
            'boolean_true'  => true,
            'boolean_false' => false,
            'null'          => null,
            'object'        => (object) 'test',
            'closure'       => function () {$test = "test";},
            'float'         => 5.43323232323,
        );

        $this->defaultModel->hydrate($array);
        $this->assertEquals($array, $this->defaultModel->extract());

        $this->defaultModel->exchangeArray($array);
        $this->assertEquals($array, $this->defaultModel->extract());
    }
}
