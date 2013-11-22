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

        $config = $serviceLocator->get("Config");
        if (isset($config['authentication']['adapter'])) {
            $authenticationService->setAdapter($serviceLocator->get($config['authentication']['adapter']));
        }

        if (isset($config['authentication']['storage'])) {
            $authenticationService->setStorage($serviceLocator->get($config['authentication']['storage']));
        }

        return $authenticationService;
    }
}
