<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 16:20
 */

namespace Core42Test\Security\Csp;


use Core42\Security\Csp\Csp;
use Core42\Security\Csp\CspOptions;
use PHPUnit\Framework\TestCase;


class CspTest extends TestCase
{
    /**
     * @var CspOptions
     */
    protected $cspOptions;

    public function setUp()
    {
        $this->cspOptions = new CspOptions([

        ]);
    }

    public function testGetCspOptions()
    {
        $csp = new Csp($this->cspOptions);

        $this->assertSame($this->cspOptions, $csp->getCspOptions());
    }
}
