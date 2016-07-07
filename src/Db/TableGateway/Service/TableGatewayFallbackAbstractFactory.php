<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Service;

use Core42\Db\Metadata\Metadata;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class TableGatewayFallbackAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param string $name
     * @return bool|string
     */
    protected function getFQCN($name)
    {
        if (class_exists($name)) {
            return $name;
        }

        if (strpos($name, '\\') === false) {
            return false;
        }

        $parts = explode('\\', $name, 2);

        return '\\' . $parts[0] . '\\TableGateway\\' .$parts[1] . 'TableGateway';
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
        $fqcn = $this->getFQCN($requestedName);
        if ($fqcn === false) {
            return false;
        }

        return class_exists($fqcn);
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
        $fqcn = $this->getFQCN($requestedName);

        /* @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = $container->get('Db\Master');
        $slave = null;
        if ($container->has('Db\Slave')) {
            $slave =  $container->get('Db\Slave');
        }

        $hydratorStrategyPluginManager = $container->get(HydratorStrategyPluginManager::class);

        /** @var AbstractTableGateway $gateway */
        $gateway = new $fqcn($adapter, $hydratorStrategyPluginManager, $slave);

        if ($gateway->getUseMetaDataFeature() === true) {
            $metadata = $container->get(Metadata::class);
            $gateway->enableMetadata($metadata, $hydratorStrategyPluginManager);
        }

        $gateway->initialize();

        return $gateway;
    }
}
