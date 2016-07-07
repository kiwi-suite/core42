<?php
namespace Core42\View\Helper\Navigation\Service;

use Core42\Navigation\Navigation;
use Core42\View\Helper\Navigation\Breadcrumbs;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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
        return new Breadcrumbs($container->get(Navigation::class));
    }
}
