<?php
namespace Core42\Mvc\Controller\Plugin\Service;

use Core42\Mvc\Controller\Plugin\MobileDetect;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MobileDetectFactory implements FactoryInterface
{
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
