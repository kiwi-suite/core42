<?php
namespace Core42Test\View\Helper;

use Core42\View\Helper\Params;
use PHPUnit\Framework\TestCase;
use Zend\Http\Header\AcceptEncoding;
use Zend\Http\PhpEnvironment\Request;
use Zend\Router\Http\RouteMatch;

class ParamsTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    public function setUp()
    {
        $_GET = [
            'get_test' => '1234567'
        ];
        $_POST = [
            'post_test' => 'abcdefg',
        ];

        $_SERVER = [
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch, br',
            'SCRIPT_NAME' => 'index.php',
        ];

        $_FILES = [
            'file1' => [
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php/php12345',
                'size' => '12345',
                'error' => UPLOAD_ERR_OK,
            ]
        ];


        $this->request = new Request();

        $this->routeMatch = new RouteMatch([
            'route_param' => 'uvwxyz',
        ]);
    }

    public function testInvoke()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertEquals("uvwxyz", $params("route_param"));
        $this->assertNull($params("test"));
        $this->assertTrue($params("test", true));

        $this->assertEquals($params, $params());

        $params = new Params($this->request);
        $this->assertNull($params("route_param"));
        $this->assertTrue($params("test", true));
    }

    public function testFromFiles()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertEquals("test.jpg", $params->fromFiles("file1")['name']);
        $this->assertNull($params->fromFiles("test"));
        $this->assertTrue($params->fromFiles("test", true));

        $params = new Params($this->request);
        $this->assertEquals("test.jpg", $params->fromFiles("file1")['name']);
        $this->assertNull($params->fromFiles("test"));
        $this->assertTrue($params->fromFiles("test", true));
    }

    public function testFromHeader()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertInstanceOf(AcceptEncoding::class, $params->fromHeader("accept_encoding"));
        $this->assertArrayHasKey("Accept-Encoding", $params->fromHeader());
        $this->assertNull($params->fromHeader("doesnt_exist"));
        $this->assertTrue($params->fromHeader("doesnt_exist", true));

        $params = new Params($this->request);

        $this->assertInstanceOf(AcceptEncoding::class, $params->fromHeader("accept_encoding"));
        $this->assertArrayHasKey("Accept-Encoding", $params->fromHeader());
        $this->assertNull($params->fromHeader("doesnt_exist"));
        $this->assertTrue($params->fromHeader("doesnt_exist", true));
    }

    public function testFromPost()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertEquals("abcdefg", $params->fromPost("post_test"));
        $this->assertNull($params->fromPost("test"));
        $this->assertTrue($params->fromPost("test", true));

        $params = new Params($this->request);
        $this->assertEquals("abcdefg", $params->fromPost("post_test"));
        $this->assertNull($params->fromPost("test"));
        $this->assertTrue($params->fromPost("test", true));
    }

    public function testFromQuery()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertEquals("1234567", $params->fromQuery("get_test"));
        $this->assertNull($params->fromQuery("test"));
        $this->assertTrue($params->fromQuery("test", true));

        $params = new Params($this->request);
        $this->assertEquals("1234567", $params->fromQuery("get_test"));
        $this->assertNull($params->fromQuery("test"));
        $this->assertTrue($params->fromQuery("test", true));
    }

    public function testFromRoute()
    {
        $params = new Params($this->request, $this->routeMatch);
        $this->assertEquals("uvwxyz", $params->fromRoute("route_param"));
        $this->assertNull($params->fromRoute("test"));
        $this->assertTrue($params->fromRoute("test", true));

        $params = new Params($this->request);
        $this->assertNull($params->fromRoute("route_param"));
        $this->assertTrue($params->fromRoute("test", true));
    }
}
