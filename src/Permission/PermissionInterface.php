<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */


namespace Core42\Permission;

use Zend\Permissions\Rbac\AssertionInterface;

interface PermissionInterface
{
    /**
     * @param  string|RoleInterface               $child
     * @param  array|RoleInterface|null           $parents
     * @return self
     */
    public function addRole($child, $parents = null);

    /**
     * Is a child with $name registered?
     *
     * @param  \Zend\Permissions\Rbac\RoleInterface|string $objectOrName
     * @return bool
     */
    public function hasRole($objectOrName);

    /**
     * @param  \Zend\Permissions\Rbac\RoleInterface|string $objectOrName
     * @return RoleInterface
     */
    public function getRole($objectOrName);

    /**
     * @return array
     */
    public function getRoles();

    /**
     * @return string
     */
    public function getGuestRole();

    /**
     * @return IdentityRoleProviderInterface
     */
    public function getIdentity();

    /**
     * @param string $permission
     * @param string|AssertionInterface|callable|null $assert
     * @param array $params
     * @param string $role
     * @return bool
     */
    public function authorized($permission, $assert = null, array $params = [], $role = null);
}
