<?php
namespace Core42\Authentication\Service;

use Core42\Authentication\Authentication;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticationService = new Authentication();

        $serviceLocator->addAbstractFactory('Core42\Authentication\Service\PluginAbstractFactory');

        $config = $serviceLocator->get('Core42\AuthenticationConfig');
        if (isset($config['adapter'])) {
            $authenticationService->setAdapter($serviceLocator->get($config['adapter']));
        }

        if (isset($config['storage'])) {
            $authenticationService->setStorage($serviceLocator->get($config['storage']));
        }

        return $authenticationService;
    }
}
