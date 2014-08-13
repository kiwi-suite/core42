<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

/**
 * Simple implementation for a hierarchical role
 */
class HierarchicalRole extends Role implements HierarchicalRoleInterface
{
    /**
     * @var array|RoleInterface[]
     */
    protected $children = array();

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @return array|RoleInterface[]|\Traversable
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param RoleInterface $child
     */
    public function addChild(RoleInterface $child)
    {
        $this->children[$child->getName()] = $child;
    }
}
