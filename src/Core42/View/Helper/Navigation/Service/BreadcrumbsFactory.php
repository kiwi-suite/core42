<?php
namespace Core42\View\Helper\Navigation\Service;

use Core42\View\Helper\Navigation\Breadcrumbs;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BreadcrumbsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Breadcrumbs
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Breadcrumbs($serviceLocator->getServiceLocator()->get('Core42\Navigation'));
    }
}
