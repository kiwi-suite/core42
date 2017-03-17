<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/03/2017
 * Time: 11:44
 */

namespace Core42Test\View\Helper\Service;


use Core42\Permission\Service\PermissionPluginManager;
use Core42\View\Helper\Permission;
use Core42\View\Helper\Service\PermissionFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;


class PermissionFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $permissionFactory = new PermissionFactory();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            PermissionPluginManager::class,
            new PermissionPluginManager(new ServiceManager())
        );

        $this->assertInstanceOf(Permission::class, $permissionFactory($serviceManager, Permission::class, []));
    }
}
