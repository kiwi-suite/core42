<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac;

use Core42\Permission\Rbac\Guard\GuardInterface;
use Core42\Permission\Rbac\Options\RbacOptions;
use Zend\ServiceManager\ServiceManager;

class RbacManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @var AuthorizationService[]
     */
    private $services = [];

    /**
     * @var RbacOptions[]
     */
    private $rbacOptions = [];

    /**
     * @param ServiceManager $serviceManager
     * @param array $config
     */
    public function __construct(ServiceManager $serviceManager, array $config)
    {
        $this->config = $config;

        $this->serviceManager = $serviceManager;
    }

    /**
     * @param string $name
     * @return AuthorizationService
     * @throws \Exception
     */
    public function getService($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        $options = $this->getRbacOptions($name);

        $identityRoleProvider = $this->serviceManager->get($options->getIdentityRoleProvider());

        $roleProviderPluginManager = $this->serviceManager->get('Core42\Permission\RoleProviderPluginManager');

        $roleProviderConfig = $options->getRoleProvider();
        $roleProvider = $roleProviderPluginManager->get(
            $roleProviderConfig['name'],
            (!empty($roleProviderConfig['options'])) ? $roleProviderConfig['options'] : []
        );

        $rbac = $this->serviceManager->get('Core42\Rbac');
        $assertionManager = $this->serviceManager->get('Core42\Permission\AssertionPluginManager');

        $service = new AuthorizationService(
            $name,
            $rbac,
            $identityRoleProvider,
            $roleProvider,
            $options->getGuestRole(),
            $assertionManager
        );

        $assertionMap = $options->getAssertionMap();
        $assertionMap['RouteAssertion'] = 'RouteAssertion';
        $service->setAssertions($assertionMap);

        $this->services[$name] = $service;

        return $service;
    }

    /**
     * @param string $name
     * @return GuardInterface[]
     * @throws \Exception
     */
    public function getGuards($name)
    {
        $initializedGuards = [];

        $options = $this->getRbacOptions($name);

        $guards = $options->getGuards();

        $guardPluginManager = $this->serviceManager->get('Core42\Permission\GuardPluginManager');

        foreach ($guards as $guardName => $options) {
            /** @var GuardInterface $currentGuard */
            $currentGuard = $guardPluginManager->get($guardName);
            $currentGuard->setAuthorizationServiceName($name);
            $currentGuard->setRbacManager($this);
            $currentGuard->setOptions($options);

            $initializedGuards[] = $currentGuard;
        }

        return $initializedGuards;
    }

    /**
     * @param $name
     * @return RbacOptions
     * @throws \Exception
     */
    public function getRbacOptions($name)
    {
        if (isset($this->rbacOptions[$name])) {
            return $this->rbacOptions[$name];
        }

        if (!array_key_exists($name, $this->config['service'])) {
            throw new \Exception("Permission '{$name} isn't defined");
        }

        $config = $this->config['service'][$name];
        $options = new RbacOptions($config);

        $this->rbacOptions[$name] = $options;

        return $this->rbacOptions[$name];
    }
}
