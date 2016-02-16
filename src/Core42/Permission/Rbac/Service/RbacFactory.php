<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Service;

use Core42\Permission\Rbac\Rbac;
use Core42\Permission\Rbac\Traversal\Strategy\RecursiveRoleIteratorStrategy;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RbacFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Rbac
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Rbac(new RecursiveRoleIteratorStrategy());
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Rbac
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Rbac::class);
    }
}
