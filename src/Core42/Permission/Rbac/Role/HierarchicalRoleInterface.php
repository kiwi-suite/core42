<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

use Traversable;

/**
 * Interface for a hierarchical role
 *
 * A hierarchical role is a role that can have children.
 */
interface HierarchicalRoleInterface extends RoleInterface
{
    /**
     * Check if the role has children
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Get child roles
     *
     * @return array|RoleInterface[]|Traversable
     */
    public function getChildren();
}
