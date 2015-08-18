<?php
namespace Core42\View\Helper\Navigation\Service;

use Core42\View\Helper\Navigation\Menu;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Menu
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Menu($serviceLocator->getServiceLocator()->get('Core42\Navigation'));
    }
}
