<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Traversal;

use ArrayIterator;
use Core42\Permission\Rbac\Role\HierarchicalRoleInterface;
use Core42\Permission\Rbac\Role\RoleInterface;
use RecursiveIterator;
use Traversable;

class RecursiveRoleIterator extends ArrayIterator implements RecursiveIterator
{
    /**
     * @param RoleInterface[]|Traversable $roles
     */
    public function __construct($roles)
    {
        if ($roles instanceof Traversable) {
            $roles = iterator_to_array($roles);
        }

        parent::__construct($roles);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->current() instanceof RoleInterface;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        $current = $this->current();

        if (!$current instanceof HierarchicalRoleInterface) {
            return false;
        }

        return $current->hasChildren();
    }

    /**
     * @return RecursiveRoleIterator
     */
    public function getChildren()
    {
        return new RecursiveRoleIterator($this->current()->getChildren());
    }
}
