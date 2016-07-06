<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac;

use Core42\Permission\Rbac\Role\RoleInterface;
use Core42\Permission\Rbac\Traversal\Strategy\TraversalStrategyInterface;
use Traversable;

/**
 * Rbac object. It is used to check a permission against roles
 */
class Rbac
{
    /**
     * @var TraversalStrategyInterface
     */
    protected $traversalStrategy;

    /**
     * @param TraversalStrategyInterface $strategy
     */
    public function __construct(TraversalStrategyInterface $strategy)
    {
        $this->traversalStrategy = $strategy;
    }

    /**
     * Determines if access is granted by checking the roles for permission.
     *
     * @param  RoleInterface|RoleInterface[]|Traversable $roles
     * @param  mixed                                     $permission
     * @return bool
     */
    public function isGranted($roles, $permission)
    {
        if ($roles instanceof RoleInterface) {
            $roles = [$roles];
        }

        $iterator = $this->traversalStrategy->getRolesIterator($roles);

        foreach ($iterator as $role) {
            /* @var RoleInterface $role */
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the strategy.
     *
     * @return TraversalStrategyInterface
     */
    public function getTraversalStrategy()
    {
        return $this->traversalStrategy;
    }
}
