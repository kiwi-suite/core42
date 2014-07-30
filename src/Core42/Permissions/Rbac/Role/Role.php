<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permissions\Rbac\Role;

class Role extends \Zend\Permissions\Rbac\Role implements RoleInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * returns the name of the route where the user should redirected in case he has no permission
     *
     * @return string
     */
    public function getPermissionDeniedRoute()
    {
        return $this->getOption('permission_denied_route');
    }

    /**
     * returns the name of the route where the user should be redirected after a successful login
     *
     * @return string
     */
    public function getLoginRoute()
    {
        return $this->getOption('login_route');
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (!array_key_exists($name, $this->options)) {
            return $default;
        }

        return $this->options[$name];
    }
}
