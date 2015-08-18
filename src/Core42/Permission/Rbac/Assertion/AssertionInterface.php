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

interface AssertionInterface
{
    /**
     * @param  AuthorizationService $authorizationService
     * @return bool
     */
    public function assert(AuthorizationService $authorizationService);
}
