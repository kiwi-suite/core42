<?php
namespace Core42\Permissions\Rbac\Role;

interface RoleInterface extends \Zend\Permissions\Rbac\RoleInterface
{
    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options);

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * returns the name of the route where the user should redirected in case he has no permission
     *
     * @return string
     */
    public function getPermissionDeniedRoute();

    /**
     * returns the name of the route where the user should be redirected after a successful login
     *
     * @return string
     */
    public function getLoginRoute();
}
