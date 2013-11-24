<?php
namespace Core42\Permissions\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl
{
    /**
     * @var string
     */
    private $identityRole;

    /**
     * @param  string                      $identityRole
     * @return \Core42\Permissions\Acl\Acl
     */
    public function setIdentityRole($identityRole)
    {
        $this->identityRole = $identityRole;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityRole()
    {
        return $this->identityRole;
    }

    /**
     * @param $resource
     * @param  null $permissions
     * @return bool
     */
    public function isIdentityAllowed($resource, $permissions = null)
    {
        return $this->isAllowed($this->getIdentityRole(), $resource, $permissions);
    }
}
