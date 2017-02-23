<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
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

    /**
     * @param string $name
     * @return $this
     */
    public function __invoke($name)
    {
        $this->serviceName = $name;

        return $this;
    }

    /**
     * @param $permission
     * @param null|string $assert
     * @param array $params
     * @param string $role
     * @return bool
     */
    public function authorized($permission, $assert = null, array $params = [], $role = null)
    {
        return $this
            ->permissionPluginManager
            ->get($this->serviceName)
            ->authorized($permission, $assert, $params, $role);
    }
}
