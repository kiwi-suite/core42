<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac;

use Core42\Permission\Rbac\Assertion\AssertionInterface;
use Core42\Permission\Rbac\Assertion\AssertionPluginManager;
use Core42\Permission\Rbac\Identity\IdentityRoleProviderInterface;
use Core42\Permission\Rbac\Role\RoleInterface;
use Core42\Permission\Rbac\Role\RoleProviderInterface;
use Traversable;

class AuthorizationService
{
    /**
     * @var Rbac
     */
    private $rbac;

    /**
     * @var RoleProviderInterface
     */
    private $roleProvider;

    /**
     * @var IdentityRoleProviderInterface
     */
    private $identityRoleProvider;

    /**
     * @var string
     */
    private $guestRole;

    /**
     * @var AssertionPluginManager
     */
    private $assertionManager;

    /**
     * @var array
     */
    protected $assertions = array();

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @param Rbac $rbac
     * @param IdentityRoleProviderInterface $identityRoleProvider
     * @param RoleProviderInterface $roleProvider
     * @param $guestRole
     * @param AssertionPluginManager $assertionManager
     */
    public function __construct(
        $name,
        Rbac $rbac,
        IdentityRoleProviderInterface $identityRoleProvider,
        RoleProviderInterface $roleProvider,
        $guestRole,
        AssertionPluginManager $assertionManager
    ) {
        $this->name = $name;

        $this->rbac = $rbac;

        $this->roleProvider = $roleProvider;

        $this->identityRoleProvider = $identityRoleProvider;

        $this->guestRole = $guestRole;

        $this->assertionManager = $assertionManager;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $permission
     * @param mixed $context
     * @return boolean
     */
    public function isGranted($permission, $context = null)
    {
        $roles = $this->getIdentityRoles();

        if (empty($roles)) {
            return false;
        }

        if ($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[(string) $permission], $context);
        }

        if (!$this->rbac->isGranted($roles, $permission)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getGuestRole()
    {
        return $this->guestRole;
    }

    /**
     * Get the identity roles from the current identity, applying some more logic
     *
     * @return RoleInterface[]
     */
    public function getIdentityRoles()
    {
        $roles = $this->identityRoleProvider->getRoles();

        if (empty($roles)) {
            return $this->convertRoles([$this->guestRole]);
        }

        return $this->convertRoles($roles);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function matchIdentityRoles(array $roles)
    {
        $identityRoles = $this->getIdentityRoles();

        if (empty($identityRoles)) {
            return false;
        }

        $roleNames = array();

        foreach ($roles as $role) {
            $roleNames[] = $role instanceof RoleInterface ? $role->getName() : (string) $role;
        }

        $identityRoles = $this->flattenRoles($identityRoles);

        return count(array_intersect($roleNames, $identityRoles)) > 0;
    }

    /**
     * @param  array|Traversable $roles
     * @return RoleInterface[]
     */
    protected function convertRoles($roles)
    {
        if ($roles instanceof Traversable) {
            $roles = iterator_to_array($roles);
        }

        $collectedRoles = array();
        $toCollect      = array();

        foreach ((array) $roles as $role) {
            if ($role instanceof RoleInterface) {
                $collectedRoles[] = $role;
                continue;
            }

            $toCollect[] = (string) $role;
        }

        if (empty($toCollect)) {
            return $collectedRoles;
        }

        return array_merge($collectedRoles, $this->roleProvider->getRoles($toCollect));
    }

    /**
     * @param  array|RoleInterface[] $roles
     * @return string[]
     */
    public function flattenRoles(array $roles)
    {
        $roleNames = array();
        $iterator  = $this->rbac->getTraversalStrategy()->getRolesIterator($roles);

        foreach ($iterator as $role) {
            $roleNames[] = $role->getName();
        }

        return array_unique($roleNames);
    }

    /**
     * @param string $permission
     * @param string|callable|AssertionInterface $assertion
     * @return void
     */
    public function setAssertion($permission, $assertion)
    {
        $this->assertions[(string) $permission] = $assertion;
    }

    /**
     * @param array $assertions
     * @return void
     */
    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasAssertion($permission)
    {
        return isset($this->assertions[(string) $permission]);
    }

    /**
     * @param  string|callable|AssertionInterface $assertion
     * @param  mixed $context
     * @return bool
     * @throws \Exception If an invalid assertion is passed
     */
    protected function assert($assertion, $context = null)
    {
        if (is_callable($assertion)) {
            return $assertion($this, $context);
        } elseif ($assertion instanceof AssertionInterface) {
            return $assertion->assert($this, $context);
        } elseif (is_string($assertion)) {
            $assertion = $this->assertionManager->get($assertion);

            return $assertion->assert($this, $context);
        }

        throw new \Exception(sprintf(
            'Assertion must be callable, string or implement Core42\Permission\Rbac\Assertion\AssertionInterface, "%s" given',
            is_object($assertion) ? get_class($assertion) : gettype($assertion)
        ));
    }
}
