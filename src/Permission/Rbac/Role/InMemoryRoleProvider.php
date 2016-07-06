<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

class InMemoryRoleProvider implements RoleProviderInterface
{
    /**
     * @var array
     */
    private $rolesConfig = [];

    /**
     * @param array
     */
    public function __construct(array $rolesConfig)
    {
        $this->rolesConfig = $rolesConfig;
    }

    /**
     * @param array $roleNames
     * @return array|RoleInterface[]
     */
    public function getRoles(array $roleNames)
    {
        $roles = [];

        foreach ($roleNames as $roleName) {
            if (!isset($this->rolesConfig[$roleName])) {
                $roles[] = new Role($roleName);
                continue;
            }

            $roleConfig = $this->rolesConfig[$roleName];

            $roleOptions = (!empty($roleConfig['options'])) ? $roleConfig['options'] : [];

            if (isset($roleConfig['children'])) {
                $role       = new HierarchicalRole($roleName, $roleOptions);
                $childRoles = (array) $roleConfig['children'];

                foreach ($this->getRoles($childRoles) as $childRole) {
                    $role->addChild($childRole);
                }
            } else {
                $role = new Role($roleName, $roleOptions);
            }

            $permissions = isset($roleConfig['permissions']) ? $roleConfig['permissions'] : [];

            foreach ($permissions as $permission) {
                $role->addPermission($permission);
            }

            $roles[] = $role;
        }

        return $roles;
    }

    /**
     * @return array|RoleInterface[]
     */
    public function getAllRoles()
    {
        return $this->getRoles(array_keys($this->rolesConfig));
    }
}
