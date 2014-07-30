<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Authentication;

use Core42\Permissions\Rbac\Role\RoleAwareInterface;
use Zend\Authentication\AuthenticationService;

class Authentication extends AuthenticationService implements RoleAwareInterface
{

    /**
     * @return string|null
     */
    public function getIdentityRole()
    {
        if ($this->getStorage() instanceof RoleAwareInterface) {
            return $this->getStorage()->getIdentityRole();
        }

        if ($this->hasIdentity() && $this->getIdentity() instanceof RoleAwareInterface) {
            return $this->getIdentity()->getIdentityRole();
        }

        return null;
    }
}
