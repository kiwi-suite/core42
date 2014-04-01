<?php
namespace Core42\Permissions\Rbac\Role;

interface RoleAwareInterface
{
    public function getIdentityRole();
}
