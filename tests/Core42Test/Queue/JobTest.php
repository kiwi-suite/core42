<?php
namespace Core42Test\Queue;

use Core42\Queue\Job;

class JobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Job
     */
    protected $job;

    public function setUp()
    {
        $this->job = new Job();
    }

    /**
     * @covers Core42\Queue\Job::setServiceName
     * @covers Core42\Queue\Job::getServiceName
     */
    public function testSetGetServiceName()
    {
        $serviceName = "Core\\Test";
        $this->job->setServiceName($serviceName);

        $this->assertEquals($this->job->getServiceName(), $serviceName);
    }

    /**
     * @covers Core42\Queue\Job::setParams
     * @covers Core42\Queue\Job::getParams
     */
    public function testSetGetParams()
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

        $this->job->setParams($array);

        $this->assertEquals($this->job->getParams(), $array);
    }

    /**
     * @covers Core42\Queue\Job::factory
     */
    public function testFactory()
    {
        $serviceName = "Core\\Test";
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

        $job = Job::factory($serviceName, $array);

        $this->assertInstanceOf('\Core42\Queue\Job', $job);

        $this->assertEquals($serviceName, $job->getServiceName());
        $this->assertEquals($array, $job->getParams());
    }
}
