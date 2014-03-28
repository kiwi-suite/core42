<?php
namespace Core42\Permissions\Rbac\Service;

use Core42\Permissions\Guard\GuardInterface;
use Core42\Permissions\Rbac\Provider\Role\RoleProviderInterface;
use Core42\Permissions\Rbac\Rbac;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RbacFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $rbacConfig = $serviceLocator->get('Core42\Permission\Config');
        $rbac = new Rbac();

        if (!empty($rbacConfig) && $rbacConfig['enabled'] === true) {
            $rbac->isEnabled($rbacConfig['enabled']);

            foreach ($rbacConfig['guards'] as $serviceName => $options) {

                /** @var $guard GuardInterface */
                $guard = $serviceLocator->get($serviceName);
                $guard->setOptions($options);
                $rbac->addGuard($guard);
            }

            /** @var $roleProvider RoleProviderInterface */
            $roleProvider = $serviceLocator->get($rbacConfig['role_provider']);
            $roles = $roleProvider->getRoles();
            foreach ($roles as $_role) {
                $rbac->addRole($_role);
            }
        }

        return $rbac;
    }
}
