<?php
namespace Core42\Hydrator\Strategy\Database\MySQL\Service;

use Core42\Hydrator\Strategy\Database\MySQL\JsonStrategy;
use Core42\Hydrator\Strategy\Service\JsonHydratorPluginManager;
use Core42\Serializer\Adapter\Json;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Serializer\Serializer;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class JsonStrategyFactory implements FactoryInterface
{

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
        $serializer = Serializer::factory(new Json(null, $container->get(JsonHydratorPluginManager::class)));

        return new JsonStrategy($serializer);
    }
}
