<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 09:22
 */

namespace Core42Test\View\Helper;


use Core42\Security\Csp\Csp;
use Core42\Security\Csp\CspOptions;
use Core42\View\Helper\InlineScript;
use Core42\View\Helper\Proxy;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;
use Zend\View\Renderer\PhpRenderer;


class InlineScriptTest extends TestCase
{
    protected $renderer;

    public function setUp()
    {
        $this->renderer = new PhpRenderer();

        $helperPluginManager = new HelperPluginManager(new ServiceManager());

        $cspOptions = new CspOptions();
        $cspOptions->setEnable(true);
        $cspOptions->setNonce(true);
        $csp = new Csp($cspOptions);
        $proxy = new Proxy($csp);
        $helperPluginManager->setService("csp", $proxy);

        $this->renderer->setHelperPluginManager($helperPluginManager);
    }

    public function testCreateData()
    {
        $inlineScript = new InlineScript();
        $inlineScript->setView($this->renderer);

        $return = $inlineScript->createData("type", [], "test");

        $this->assertArrayHasKey("nonce", $return->attributes);
    }
}
