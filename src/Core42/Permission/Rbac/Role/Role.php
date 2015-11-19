<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

class Role implements RoleInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $permissions = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = [])
    {
        $this->name = (string) $name;

        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $permission
     */
    public function addPermission($permission)
    {
        $this->permissions[(string) $permission] = $permission;
    }

    /**
     * @param  string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permission = (string) $permission;
        if (isset($this->permissions[$permission])) {
            return true;
        }

        $permissionParts = explode("/", $permission);

        for ($i = 0; $i < count($permissionParts); $i++) {
            $checkPermission = [];
            for ($j = 0; $j <= $i; $j++) {
                $checkPermission[] = $permissionParts[$j];

                if (isset($this->permissions[implode("/", $checkPermission) . '*'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
