<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Traversal\Strategy;

use Core42\Permission\Rbac\Role\HierarchicalRoleInterface;
use Core42\Permission\Rbac\Role\RoleInterface;
use Generator;
use Traversable;

class GeneratorStrategy implements TraversalStrategyInterface
{
    /**
     * @param  RoleInterface[]|Traversable $roles
     * @return Generator
     */
    public function getRolesIterator($roles)
    {
        foreach ($roles as $role) {
            yield $role;

            if (!$role instanceof HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->getRolesIterator($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }
}
