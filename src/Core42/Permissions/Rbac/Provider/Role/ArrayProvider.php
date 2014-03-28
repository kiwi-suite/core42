<?php
namespace Core42\Permissions\Rbac\Provider\Role;

use Core42\Permissions\Rbac\Role\RoleInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class ArrayProvider implements RoleProviderInterface,
                                ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;


    /**
     * @return RoleInterface[]
     */
    public function getRoles()
    {
        $config = $this->serviceManager->get('Core42\Permission\Config');

        if (!isset($config['roles'])) {
            return array();
        }

        return $this->getRolesRecursive($config['roles']);
    }

    protected function getRolesRecursive($config)
    {
        $roles = array();
        foreach ($config as $roleName => $params) {
            $type = (isset($params['type'])) ? $params['type'] : '\Core42\Permissions\Rbac\Role\Role';

            /** @var $role RoleInterface */
            $role = new $type($roleName);

            if (isset($params['options'])) {
                $role->setOptions($params['options']);
            }

            if (isset($params['children'])) {
                $children = $this->getRolesRecursive($params['children']);
                foreach ($children as $childRole) {
                    $role->addChild($childRole);
                }
            }

            $roles[] = $role;
        }

        return $roles;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}
