<?php
namespace Core42\Permissions\Rbac\Role;

interface RoleAwareInterface
{
    /**
     * @return string|null
     */
    public function getIdentityRole();
}
