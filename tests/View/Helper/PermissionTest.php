<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 06:06
 */

namespace Core42Test\View\Helper;


use Core42\Permission\Service\PermissionPluginManager;
use Core42\View\Helper\Permission;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceManager;


class PermissionTest extends TestCase
{
    /**
     * @var PermissionPluginManager
     */
    protected $permissionPluginManager;

    public function setUp()
    {
        $serviceManager = new ServiceManager();
        $config = [];

        $this->permissionPluginManager = new PermissionPluginManager($serviceManager, $config);
        $this->permissionPluginManager->setService("perm", new PermissionMock());
    }

    public function testInvoke()
    {
        $permission = new Permission($this->permissionPluginManager);
        $this->assertSame($permission, $permission("perm"));

        $permission = new Permission($this->permissionPluginManager);
        $this->assertSame($permission, $permission(null));
    }

    public function testAuthorized()
    {
        $permission = new Permission($this->permissionPluginManager);
        $permission("perm");

        $this->assertTrue($permission->authorized("authorized"));
        $this->assertTrue($permission->authorized("authorized", null, [], "user"));
        $this->assertFalse($permission->authorized("not-authorized"));
        $this->assertFalse($permission->authorized("not-authorized", null, [], "user"));
        $this->assertFalse($permission->authorized("authorized", null, [], "guest"));

        $this->assertTrue($permission->authorized(null, "authorized", []));
        $this->assertFalse($permission->authorized(null, "not-authorized", []));
        $this->assertFalse($permission->authorized(null, "authorized", ["always-invalid" => true]));
    }

    public function testInvalidPermissionService()
    {
        $this->expectException(ServiceNotFoundException::class);
        $permission = new Permission($this->permissionPluginManager);

        $permission("12345");
        $permission->authorized("authorized");
    }
}
