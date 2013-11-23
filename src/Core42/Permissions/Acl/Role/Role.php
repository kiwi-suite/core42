<?php
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
     * @param array $options
     */
    public function __construct($roleId, $options = array())
    {
        $this->roleId = (string) $roleId;
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
