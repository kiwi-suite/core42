<?php
namespace Core42\Permissions\Rbac\Service;

use Core42\Permissions\Rbac\Provider\Role\RoleProviderInterface;
use Core42\Permissions\Rbac\Rbac;
use Core42\Permissions\Rbac\Role\RoleAwareInterface;
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

            /** @var $roleProvider RoleProviderInterface */
            $roleProvider = $serviceLocator->get($rbacConfig['role_provider']);
            $roles = $roleProvider->getRoles();
            foreach ($roles as $_role) {
                $rbac->addRole($_role);
            }

            if (!empty($rbacConfig['authentication_service'])) {
                /** @var $authenticationService \Zend\Authentication\AuthenticationService */
                $authenticationService = $serviceLocator->get($rbacConfig['authentication_service']);
                if ($authenticationService->hasIdentity()) {
                    $rbac->setIdentityRole($rbacConfig['default_authenticated_role']);
                } else {
                    $rbac->setIdentityRole($rbacConfig['default_unauthenticated_role']);
                }
            } else {
                $rbac->setIdentityRole($rbacConfig['default_unauthenticated_role']);
            }

            if (!empty($rbacConfig['identity_provider'])) {
                /** @var $identityProvider RoleAwareInterface */
                $identityProvider = $serviceLocator->get($rbacConfig['identity_provider']);
                if ($identityProvider instanceof RoleAwareInterface && $identityProvider->getIdentityRole() !== null) {
                    $rbac->setIdentityRole($identityProvider->getIdentityRole());
                }
            }
        }

        return $rbac;
    }
}
