<?php
namespace Core42\Permissions\Rbac;

use Core42\Permissions\Guard\GuardInterface;

class Rbac extends \Zend\Permissions\Rbac\Rbac
{
    /**
     * @var bool
     */
    private $isEnabled = false;

    /**
     * @var GuardInterface[]
     */
    private $guards = array();

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
     * @param GuardInterface $guard
     *  @return \Core42\Permissions\Rbac\Rbac
     */
    public function addGuard(GuardInterface $guard)
    {
        $this->guards[] = $guard;

        return $this;
    }

    /**
     * @return \Core42\Permissions\Guard\GuardInterface[]
     */
    public function getGuards()
    {
        return $this->guards;
    }
}
