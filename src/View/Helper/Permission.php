<?php
namespace Core42\View\Helper;

use Admin42\Link\LinkProvider;
use Admin42\TableGateway\LinkTableGateway;
use Core42\Permission\Service\PermissionPluginManager;
use Zend\Cache\Storage\StorageInterface;
use Zend\Json\Json;
use Zend\View\Helper\AbstractHelper;

class Permission extends AbstractHelper
{
    /**
     * @var PermissionPluginManager
     */
    protected $permissionPluginManager;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @param PermissionPluginManager $permissionPluginManager
     */
    public function __construct(PermissionPluginManager $permissionPluginManager)
    {
        $this->permissionPluginManager = $permissionPluginManager;
    }

    public function __invoke($name)
    {
        $this->serviceName = $name;
        return $this;
    }

    public function isGranted($permission, $assert = null, $role = null)
    {
        return $this->permissionPluginManager->get($this->serviceName)->isGranted($permission, $assert, $role);
    }
}
