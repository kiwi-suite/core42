<?php
namespace Core42\Permissions\Rbac\Provider\Role;

use Core42\Permissions\Rbac\Role\RoleInterface;

interface RoleProviderInterface
{
    /**
     * @return RoleInterface[]
     */
    public function getRoles();
}
