<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

interface RoleInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param  mixed $permission
     * @return bool
     */
    public function hasPermission($permission);

    /**
     * @return array
     */
    public function getPermissions();

    /**
     * @return array
     */
    public function getOptions();
}
