<?php
namespace Core42Test\Stdlib;

use Core42\Stdlib\WebcrawlerCheck;
use PHPUnit\Framework\TestCase;

class WebcrawlerCheckTest extends TestCase
{
    public function setUp()
    {
    }

    public function testApple()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Applebot/0.3; +http://www.apple.com/go/applebot)', '17.138.57.22');
        $this->assertEquals('Apple', $result);

        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Applebot/0.3; +http://www.apple.com/go/applebot)', '17.138.57.22');
        $this->assertEquals('Apple', $result);

        $result = WebcrawlerCheck::check('Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Applebot/0.3; +http://www.apple.com/go/applebot)', '127.0.0.1');
        $this->assertEquals(false, $result);
    }

    public function testBing()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', '157.55.39.107');
        $this->assertEquals('Bing', $result);

        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', '127.0.0.1');
        $this->assertEquals(false, $result);
    }

    public function testYahoo()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', '68.180.230.37');
        $this->assertEquals('Yahoo', $result);

        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', '127.0.0.1');
        $this->assertEquals(false, $result);
    }

    public function testGoogle()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', '66.249.79.49');
        $this->assertEquals('Google', $result);

        $result = WebcrawlerCheck::check('DoCoMo/2.0 N905i(c100;TB;W24H16) (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', '66.249.65.228');
        $this->assertEquals('Google', $result);

        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', '127.0.0.1');
        $this->assertEquals(false, $result);
    }

    public function testUnknown()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Fakebot/2.1; +http://www.google.com/bot.html)', '192.168.1.1');
        $this->assertEquals(false, $result);
    }

    public function testCache()
    {
        $result = WebcrawlerCheck::check('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', '66.249.79.49');
        $this->assertEquals('Google', $result);

        $result = WebcrawlerCheck::check('DoCoMo/2.0 N905i(c100;TB;W24H16) (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', '66.249.79.49');
        $this->assertEquals('Google', $result);
    }
}
