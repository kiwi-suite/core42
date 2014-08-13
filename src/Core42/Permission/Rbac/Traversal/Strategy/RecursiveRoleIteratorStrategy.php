<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Traversal\Strategy;

use Core42\Permission\Rbac\Role\RoleInterface;
use Core42\Permission\Rbac\Traversal\RecursiveRoleIterator;
use RecursiveIteratorIterator;

class RecursiveRoleIteratorStrategy implements TraversalStrategyInterface
{
    /**
     * @param  RoleInterface[]           $roles
     * @return RecursiveIteratorIterator
     */
    public function getRolesIterator($roles)
    {
        return new RecursiveIteratorIterator(
            new RecursiveRoleIterator($roles),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }
}
