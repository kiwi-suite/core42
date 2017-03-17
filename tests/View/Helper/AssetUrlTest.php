<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 10:15
 */

namespace Core42Test\View\Helper;


use Core42\View\Helper\AssetUrl;
use PHPUnit\Framework\TestCase;


class AssetUrlTest extends TestCase
{
    protected $config = [];

    protected $assetUrl;

    public function setUp()
    {
        $this->config = [
            'application' =>  '/dir/test',
            'application1' => '/dir/test/',
            'application2' => 'dir/test',
            'application3' => 'dir/test/',
        ];
    }

    public function testInvoke()
    {
        $assetUrl = new AssetUrl("/test", $this->config);

        $this->assertSame("/test", $assetUrl());
        $this->assertSame("/test/file.png", $assetUrl("/file.png"));
        $this->assertSame("/test/dir/test/file.png", $assetUrl("/file.png", "application"));
        $this->assertSame("/test/dir/test", $assetUrl("", "application"));
        $this->assertSame("/test/file.png", $assetUrl("/file.png", "doesnt-exist"));
        $this->assertSame("/test", $assetUrl("", "doesnt-exist"));

        $this->assertSame("/test/dir/test", $assetUrl("", "application1"));
        $this->assertSame("/test/dir/test", $assetUrl("", "application2"));
        $this->assertSame("/test/dir/test", $assetUrl("", "application3"));
    }

    public function testSetAssetUrl()
    {
        $assetUrl = new AssetUrl("", $this->config);
        $assetUrl->setAssetUrl("test/");
        $this->assertSame("test", $assetUrl());
    }
}
