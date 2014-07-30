<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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

    /**
     * @param array $config
     * @return array
     */
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

            if (!empty($params['rules'])) {
                foreach ($params['rules'] as $_rule) {
                    $role->addPermission($_rule);
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
