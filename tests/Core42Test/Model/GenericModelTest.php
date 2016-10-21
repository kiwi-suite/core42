<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42Test\Model;

use Core42\Model\GenericModel;
use Core42\Stdlib\DateTime;

class GenericModelTest extends \PHPUnit_Framework_TestCase
{
    private $defaultModel;

    public function setUp()
    {
        $this->defaultModel = new GenericModel();
    }

    /**
     * @covers Core42\Model\DefaultModel::__call
     */
    public function testExchangeExtractHydrateArray()
    {
        $array = [
            'int'           => 1,
            'string'        => 'test',
            'datetime'      => new DateTime(),
            'array'         => [1,2,3],
            'booleanTrue'   => true,
            'booleanFalse'  => false,
            'null'          => null,
            'object'        => (object) 'test',
            'closure'       => function () {$test = "test";},

            'float'         => 5.43323232323,
        ];

        foreach ($array as $name => $value) {
            $setter = 'set' . ucfirst($name);
            $getter = 'get' . ucfirst($name);

            $this->defaultModel->{$setter}($value);

            $this->assertEquals($value, $this->defaultModel->{$getter}());
        }
    }
}
