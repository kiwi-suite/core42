<?php
namespace Core42\View\Helper\Navigation\Service;

use Core42\Navigation\Navigation;
use Core42\View\Helper\Navigation\Menu;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Menu
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Menu($container->getServiceLocator()->get(Navigation::class));
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Menu
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, "menu");
    }
}
