<?php
namespace Core42\Cache\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Stash\Pool;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class CacheAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @return array
     */
    protected function getConfig(ContainerInterface $container)
    {
        $config = $container->get('Config');
        $config = (isset($config['cache']['caches'])) ? $config['cache']['caches'] : [];

        return $config;
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $this->getConfig($container);

        return isset($config[$requestedName]);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container)[$requestedName];

        $pool = new Pool($container->get(DriverPluginManager::class)->get($config['driver']));

        if (!empty($config['namespace'])) {
            $pool->setNamespace($config['namespace']);
        }

        return $pool;
    }
}
