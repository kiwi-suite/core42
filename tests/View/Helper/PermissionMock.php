<?php
namespace Core42Test\View\Helper;

use Core42\Permission\IdentityRoleProviderInterface;
use Core42\Permission\PermissionInterface;
use Core42\Permission\RoleInterface;
use Zend\Permissions\Rbac\AssertionInterface;

class PermissionMock implements PermissionInterface
{

    /**
     * @param  string|RoleInterface $child
     * @param  array|RoleInterface|null $parents
     * @return self
     */
    public function addRole($child, $parents = null)
    {
        // TODO: Implement addRole() method.
    }

    /**
     * Is a child with $name registered?
     *
     * @param  \Zend\Permissions\Rbac\RoleInterface|string $objectOrName
     * @return bool
     */
    public function hasRole($objectOrName)
    {
        // TODO: Implement hasRole() method.
    }

    /**
     * @param  \Zend\Permissions\Rbac\RoleInterface|string $objectOrName
     * @return RoleInterface
     */
    public function getRole($objectOrName)
    {
        // TODO: Implement getRole() method.
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * @return string
     */
    public function getGuestRole()
    {
        // TODO: Implement getGuestRole() method.
    }

    /**
     * @return IdentityRoleProviderInterface
     */
    public function getIdentity()
    {
        // TODO: Implement getIdentity() method.
    }

    /**
     * @param string $permission
     * @param string|AssertionInterface|callable|null $assert
     * @param array $params
     * @param string $role
     * @return bool
     */
    public function authorized($permission, $assert = null, array $params = [], $role = null)
    {
        if (empty($role)) {
            $role = "user";
        }

        if ($assert) {
            return $this->assert($assert,  $params);
        }

        if ($role === "guest") {
            return false;
        }

        if ($permission === "authorized") {
            return true;
        }

        return false;
    }

    public function assert($assert, array $params = [])
    {
        if (isset($params['always-invalid']) && $params['always-invalid'] === true) {
            return false;
        }

        if ($assert === "authorized") {
            return true;
        }

        return false;
    }
}
