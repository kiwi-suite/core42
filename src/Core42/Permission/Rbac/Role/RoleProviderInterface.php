<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

interface RoleProviderInterface
{
    /**
     * @param  string[] $roleNames
     * @return RoleInterface[]
     */
    public function getRoles(array $roleNames);

    /**
     * @return array|RoleInterface[]
     */
    public function getAllRoles();
}
