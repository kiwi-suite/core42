<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\View\Helper;

use Core42\Permission\Service\PermissionPluginManager;
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
