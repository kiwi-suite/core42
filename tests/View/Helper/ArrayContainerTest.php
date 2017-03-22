<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 10:39
 */

namespace Core42Test\View\Helper;


use Core42\View\Helper\ArrayContainer;
use PHPUnit\Framework\TestCase;


class ArrayContainerTest extends TestCase
{
    public function testInvoke()
    {
        $arrayContainer = new ArrayContainer([]);
        $this->assertSame($arrayContainer, $arrayContainer());
    }

    public function testReset()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        $arrayContainer->filter(function($value) {
            if ($value == 'test') {
                return true;
            }

            return false;
        });
        $arrayContainer->reset();

        $this->assertSame(2, $arrayContainer->count());
    }

    public function testFilter()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        $arrayContainer->filter(function($value) {
            if ($value == 'test') {
                return true;
            }

            return false;
        });

        $this->assertSame(1, $arrayContainer->count());

        $checkArray = [];
        foreach ($arrayContainer as $value) {
            $checkArray[] = $value;
        }

        $this->assertEquals(['test'], $checkArray);
    }

    public function testSort()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        $arrayContainer->sort(function ($value1, $value2) {
            return strcmp($value1, $value2);
        }, SORT_ASC);

        $checkArray = [];
        foreach ($arrayContainer as $value) {
            $checkArray[] = $value;
        }

        $this->assertSame(['abc', 'test'], $checkArray);
    }

    public function testCount()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        $this->assertSame(2, $arrayContainer->count());
    }

    public function testCurrent()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        $this->assertSame("test", $arrayContainer->current());
        $arrayContainer->next();
        $this->assertSame("abc", $arrayContainer->current());
    }

    public function testNext()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        for ($i = 0; $i < 3; $i++) {
            $arrayContainer->next();
            $this->assertSame($i + 1, $arrayContainer->key());
        }
    }

    public function testKey()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        for ($i = 0; $i < 3; $i++) {
            $arrayContainer->next();
            $this->assertSame($i + 1, $arrayContainer->key());
        }
    }

    public function testValid()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        for ($i = 0; $i < 3; $i++) {
            if ($i < 2) {
                $this->assertTrue($arrayContainer->valid());
                $arrayContainer->next();
                continue;
            }

            $this->assertFalse($arrayContainer->valid());
            $arrayContainer->next();
        }
    }

    public function testRewind()
    {
        $array = ['test', 'abc'];
        $arrayContainer = new ArrayContainer($array);
        for ($i = 0; $i < 3; $i++) {
            $arrayContainer->next();
        }
        $arrayContainer->rewind();

        $this->assertSame(0, $arrayContainer->key());
    }
}
