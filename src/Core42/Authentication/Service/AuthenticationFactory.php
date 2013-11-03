<?php
namespace Core42\Authentication\Service;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticationService = new AuthenticationService();

        $config = $serviceLocator->get("Config");
        if (isset($config['authentication']['adapter'])) {
            switch ($config['authentication']['adapter']['name']) {
                case 'Core42\Authentication\Adapter\DbTable\BCryptCheckAdapter':
                    $config['authentication']['adapter']['options']['table_gateway'] = $serviceLocator->get($config['authentication']['adapter']['options']['table_gateway']);
                    $adapterName =  $config['authentication']['adapter']['name'];
                    $adapterOptions =  $config['authentication']['adapter']['options'];
                    $adapter = new $adapterName($adapterOptions);
                    $authenticationService->setAdapter($adapter);
                    break;
                default:
                    $adapterName =  $config['authentication']['adapter']['name'];
                    $adapterOptions =  $config['authentication']['adapter']['options'];
                    $adapter = new $adapterName($adapterOptions);
                    $authenticationService->setAdapter($adapter);
                    break;
            }
        }

        return $authenticationService;
    }
}
