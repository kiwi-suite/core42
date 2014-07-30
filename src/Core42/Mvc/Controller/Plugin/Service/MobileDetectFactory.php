<?php
namespace Core42\Mvc\Controller\Plugin\Service;

use Core42\Mvc\Controller\Plugin\MobileDetect;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MobileDetectFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MobileDetect
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mobileDetectHelper = new MobileDetect();
        $services = $serviceLocator->getServiceLocator();

        if ($services->has('MobileDetect')) {
            $mobileDetectHelper->setMobileDetect($services->get('MobileDetect'));
        }

        return $mobileDetectHelper;
    }
}
