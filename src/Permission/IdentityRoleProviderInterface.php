<?php
namespace Core42\Permission;

use Zend\Permissions\Rbac\RoleInterface;

interface IdentityRoleProviderInterface
{
    /**
     * @return string|RoleInterface
     */
    public function getRole();
}
