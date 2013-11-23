<?php
namespace Core42\Permissions\Acl\Provider;

use Core42\Permissions\Acl\Role\Role;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceManager;

class ArrayProvider implements AclProviderInterface
{

    /**
     * @param Acl $acl
     * @return null
     */
    public function provideAcl(Acl $acl, ServiceManager $serviceManager)
    {
        $config = $serviceManager->get('Core42\AclConfig');

        if (isset($config['roles'])) {
            $this->addRole($acl, $config['roles']);
        }

        if (isset($config['rules'])) {
            foreach (array('allow', 'deny') as $type) {
                if (!isset($config['rules'][$type])) {
                    continue;
                }
                foreach ($config['rules'][$type] as $rule) {
                    $role = (isset($rule[0])) ? $rule[0] : null;
                    $resource = (isset($rule[1])) ? $rule[1] : null;
                    $privilege = (isset($rule[2])) ? $rule[2] : null;

                    if ($resource !== null && !$acl->hasResource($resource)) {
                        $acl->addResource($resource);
                    }

                    $acl->{$type}($role, $resource, $privilege);
                }
            }
        }
    }

    protected function addRole(Acl $acl, $config, $parent = null)
    {
        foreach ($config as $role => $roleValues) {
            $roleObject = new Role($role, $roleValues);
            $acl->addRole($roleObject, $parent);
            if (isset($roleValues['children'])) {
                $this->addRole($acl, $roleValues['children'], $role);
            }
        }
    }
}
