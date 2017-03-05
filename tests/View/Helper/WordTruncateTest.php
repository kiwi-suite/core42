<?php
namespace Core42Test\View\Helper;

use Core42\View\Helper\WordTruncate;
use PHPUnit\Framework\TestCase;

class WordTruncateTest extends TestCase
{
    /**
     * @var WordTruncate
     */
    protected $helper;

    public function setUp()
    {
        $this->helper = new WordTruncate();
    }

    public function testInvoke()
    {
        $this->assertEquals(
            'A long time ago in a',
            $this->helper->__invoke("A long time ago in a galaxy far, far away ...", 20)
        );

        $this->assertEquals(
            'A long time ago in a galaxy far, far away ...',
            $this->helper->__invoke("A long time ago in a galaxy far, far away ...", 46)
        );

        $this->assertEquals(
            "A long time\n ago in",
            $this->helper->__invoke("A long time\n ago in a galaxy far, far away ...", 20)
        );
    }
}
