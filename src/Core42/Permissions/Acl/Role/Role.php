<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permissions\Acl\Role;

use Zend\Permissions\Acl\Role\RoleInterface;

class Role implements RoleInterface
{
    /**
     * Unique id of Role
     *
     * @var string
     */
    protected $roleId;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Sets the Role identifier
     *
     * @param string $roleId
     * @param array  $options
     */
    public function __construct($roleId, $options = array())
    {
        $this->roleId = (string) $roleId;
        $this->options = $options;
    }

    /**
     * Defined by RoleInterface; returns the Role identifier
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Defined by RoleInterface; returns the Role identifier
     * Proxies to getRoleId()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }
}
