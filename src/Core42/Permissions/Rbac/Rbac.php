<?php
namespace Core42\Permissions\Rbac;

use Core42\Permissions\Rbac\Role\RoleInterface;

class Rbac extends \Zend\Permissions\Rbac\Rbac
{
    /**
     * @var bool
     */
    private $isEnabled = false;

    /**
     * @var RoleInterface
     */
    private $idenitiyRole;


    /**
     * @param $enabled
     * @return \Core42\Permissions\Rbac\Rbac
     */
    public function isEnabled($enabled)
    {
        $this->isEnabled = $enabled;

        return $this;
    }

    /**
     * @param string|\Zend\Permissions\Rbac\RoleInterface $role
     * @param string $permission
     * @param null $assert
     * @return bool
     */
    public function isGranted($role, $permission, $assert = null)
    {
        if ($this->isEnabled === false) {
            return true;
        }

        return parent::isGranted($role, $permission, $assert);
    }

    /**
     * @param string|RoleInterface $identityRole
     * @throws \Exception
     */
    public function setIdentityRole($identityRole)
    {
        if (is_string($identityRole)) {
            $identityRole = $this->getRole($identityRole);
        }

        if (!$identityRole instanceof RoleInterface) {
            throw new \Exception("Identity-role must be a string or an existing roles");
        }

        $this->idenitiyRole = $identityRole;
    }

    /**
     * @return RoleInterface
     */
    public function getIdentityRole()
    {
        return $this->idenitiyRole;
    }

    /**
     * @param $permission
     * @param null $assert
     * @return bool
     * @throws \Exception
     */
    public function isIdentityGranted($permission, $assert = null)
    {
        if ($this->idenitiyRole === null) {
            throw new \Exception('identity role not set');
        }

        return $this->isGranted($this->getIdentityRole(), $permission, $assert);
    }
}
