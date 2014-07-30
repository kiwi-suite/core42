<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
