<?php
namespace Core42\Permissions\Acl\Service;

use Core42\Permissions\Acl\Acl;
use Core42\Permissions\Acl\Role\RoleProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Core42\AclConfig');

        $acl = new Acl();
        $acl->deny();

        if (!empty($config['authentication_service'])) {
            /** @var $authenticationService \Zend\Authentication\AuthenticationService */
            $authenticationService = $serviceLocator->get($config['authentication_service']);
            if ($authenticationService->hasIdentity()) {
                $acl->setIdentityRole($config['default_authenticated_role']);
            } else {
                $acl->setIdentityRole($config['default_unauthenticated_role']);
            }
        } else {
            $acl->setIdentityRole($config['default_unauthenticated_role']);
        }

        if (!empty($config['identity_provider'])) {
            /** @var $identityProvider \Core42\Permissions\Acl\Role\RoleProviderInterface */
            $identityProvider = $serviceLocator->get($config['identity_provider']);
            if ($identityProvider instanceof RoleProviderInterface && $identityProvider->getIdentityRole() !== null) {
                $acl->setIdentityRole($identityProvider->getIdentityRole());
            }
        }

        /** @var $provider \Core42\Permissions\Acl\Provider\AclProviderInterface */
        $provider = $serviceLocator->get($config['acl_provider']);
        $provider->provideAcl($acl, $serviceLocator);

        return $acl;
    }
}
