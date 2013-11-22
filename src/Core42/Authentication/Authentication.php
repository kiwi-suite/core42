<?php
namespace Core42\Authentication;

use Core42\Permissions\Acl\Role\RoleProviderInterface;
use Zend\Authentication\AuthenticationService;

class Authentication extends AuthenticationService implements RoleProviderInterface
{

    public function getIdentityRole()
    {
        if (!($this->getStorage() instanceof RoleProviderInterface)) {
            return null;
        }

        return $this->getStorage()->getIdentityRole();
    }
}
