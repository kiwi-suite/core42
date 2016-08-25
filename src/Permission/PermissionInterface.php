<?php
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
     * @param  string                           $permission
     * @param  AssertionInterface|Callable|null|string $assert
     * @param  RoleInterface|string             $role
     * @return bool
     */
    public function isGranted($permission, $assert = null, $role = null);
}
