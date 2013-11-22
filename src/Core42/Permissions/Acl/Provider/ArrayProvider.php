<?php
namespace Core42\Permissions\Acl\Provider;

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
            //TODO Recursive iteration
            foreach ($config['roles'] as $role => $roleValues) {
                $acl->addRole($role);
            }
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
}
