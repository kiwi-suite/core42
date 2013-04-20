<?php

class ClassTest extends \PHPUnit_Framework_TestCase
{
    public function testSurveys()
    {
        $foobar = new stdClass();
        $this->assertNotNull($foobar, 'Could not create foobar class');
    }
}