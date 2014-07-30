<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permissions\Rbac\Provider\Role;

use Core42\Permissions\Rbac\Role\RoleInterface;

interface RoleProviderInterface
{
    /**
     * @return RoleInterface[]
     */
    public function getRoles();
}
