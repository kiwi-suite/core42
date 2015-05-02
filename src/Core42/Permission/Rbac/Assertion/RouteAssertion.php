<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Assertion;

use Core42\Permission\Rbac\AuthorizationService;
use Core42\Permission\Rbac\Role\RoleInterface;

class RouteAssertion implements AssertionInterface
{

    /**
     * @param  AuthorizationService $authorizationService
     * @param string|null $routeName
     * @return bool
     */
    public function assert(AuthorizationService $authorizationService, $routeName = null)
    {
        $roles = $authorizationService->getIdentityRoles();

        $rules = [];
        /** @var RoleInterface $_role */
        foreach ($roles as $_role) {
            $permissions = $_role->getPermissions();
            foreach ($permissions as $_perm) {
                if (substr($_perm, 0, 6) !== 'route/') {
                    continue;
                }
                $rules[] = substr($_perm, 6);
            }
        }

        foreach ($rules as $routeRule) {
            if (fnmatch($routeRule, $routeName, FNM_CASEFOLD)) {
                return true;
            }
        }

        return false;
    }
}
