<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42Test\Model;

use Core42\Stdlib\DateTime;

class MigrationTest extends \PHPUnit_Framework_TestCase
{

    protected $migration;

    public function setUp()
    {
        $migration = new \Core42\Model\Migration();
        $this->migration = $migration;
    }

    /**
     * @covers Core42\Model\Migration::setName
     * @covers Core42\Model\Migration::getName
     */
    public function testSetGetName()
    {
        $result = $this->migration->setName("test");

        $this->assertEquals($result, $this->migration);

        $this->assertEquals("test", $this->migration->getName());
    }

    /**
     * @covers Core42\Model\Migration::setCreated
     * @covers Core42\Model\Migration::getCreated
     */
    public function testSetGetCreated()
    {
        $dateTime = new DateTime();

        $result = $this->migration->setCreated($dateTime);

        $this->assertEquals($result, $this->migration);

        $this->assertEquals($dateTime, $this->migration->getCreated());
    }
}
