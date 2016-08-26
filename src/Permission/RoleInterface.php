<?php
namespace Core42\Permission;

interface RoleInterface extends \Zend\Permissions\Rbac\RoleInterface
{
    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);
}
