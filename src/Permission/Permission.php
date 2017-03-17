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


namespace Core42\Permission;

use Core42\Permission\Service\AssertionPluginManager;
use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;

class Permission extends Rbac implements PermissionInterface
{
    /**
     * @var AssertionPluginManager
     */
    protected $assertionPluginManager;

    /**
     * @var string
     */
    protected $guestRole;

    /**
     * @var IdentityRoleProviderInterface
     */
    protected $identity;

    /**
     * Permission constructor.
     * @param AssertionPluginManager $assertionPluginManager
     * @param IdentityRoleProviderInterface $identity
     * @param string $guestRole
     */
    public function __construct(
        AssertionPluginManager $assertionPluginManager,
        IdentityRoleProviderInterface $identity,
        $guestRole
    ) {
        $this->assertionPluginManager = $assertionPluginManager;
        $this->identity = $identity;
        $this->guestRole = $guestRole;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = [];
        $it = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($it as $leaf) {
            /* @var RoleInterface $leaf */
            $roles[] = $leaf->getName();
        }

        return $roles;
    }

    /**
     * @param string|\Zend\Permissions\Rbac\RoleInterface $role
     * @param string $permission
     * @param null $assert
     * @return bool
     */
    public function isGranted($role, $permission, $assert = null)
    {
        return $this->authorized($permission, $assert, [], $role);
    }

    /**
     * @param string $permission
     * @param string|AssertionInterface|callable|null $assert
     * @param array $params
     * @param string $role
     * @throws \Exception
     * @return bool
     */
    public function authorized($permission, $assert = null, array $params = [], $role = null)
    {
        if ($role === null) {
            $role = $this->getIdentity()->getRole();
        }

        if ($assert) {
            return $this->assert($assert, $params);
        }

        return $this->getRole($role)->hasPermission($permission);
    }

    /**
     * @param string|AssertionInterface|callable|null $assert
     * @param array $params
     * @throws \Exception
     * @return bool
     */
    public function assert($assert, array $params = [])
    {
        if ($assert instanceof AssertionInterface) {
            return (bool) $assert->assert($this);
        }

        if (\is_callable($assert)) {
            return (bool) $assert($this);
        }

        if (\is_string($assert) && $this->assertionPluginManager->has($assert)) {
            return (bool) $this->assertionPluginManager->get($assert, $params)->assert($this);
        }

        throw new \Exception(
            'Assertions must be a Callable or an instance of Zend\Permissions\Rbac\AssertionInterface'
        );
    }

    /**
     * @return string
     */
    public function getGuestRole()
    {
        return $this->guestRole;
    }

    /**
     * @return IdentityRoleProviderInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
