<?php
namespace Core42\Authentication;

use Core42\Permissions\Acl\Role\RoleProviderInterface;
use Zend\Authentication\AuthenticationService;

class Authentication extends AuthenticationService implements RoleProviderInterface
{

    public function getIdentityRole()
    {
        if ($this->getStorage() instanceof RoleProviderInterface) {
            return $this->getStorage()->getIdentityRole();
        }

        if ($this->hasIdentity() && $this->getIdentity() instanceof RoleProviderInterface) {
            return $this->getIdentity()->getIdentityRole();
        }

        return null;
    }
}
