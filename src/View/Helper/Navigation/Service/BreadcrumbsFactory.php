<?php
namespace Core42\View\Helper\Navigation\Service;

use Core42\Navigation\Navigation;
use Core42\View\Helper\Navigation\Breadcrumbs;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BreadcrumbsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Breadcrumbs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Breadcrumbs($container->getServiceLocator()->get(Navigation::class));
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Breadcrumbs
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, "breadcrumbs");
    }
}
