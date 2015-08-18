<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Identity;

use Core42\Permission\Rbac\Role\RoleInterface;

interface IdentityRoleProviderInterface
{
    /**
     * @return string[]|RoleInterface[]
     */
    public function getRoles();
}
