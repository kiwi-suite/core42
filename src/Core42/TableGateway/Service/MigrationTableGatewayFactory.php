<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway\Service;

use Core42\Db\Metadata\Metadata;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Core42\TableGateway\MigrationTableGateway;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MigrationTableGatewayFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return MigrationTableGateway
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->getServiceLocator()->get('Db\Master');
        $slave = null;
        if ($container->getServiceLocator()->has('Db\Slave')) {
            $slave = $container->getServiceLocator()->get('Db\Slave');
        }
        $metadata = $container->getServiceLocator()->get(Metadata::class);

        $sm = $container->getServiceLocator();
        $hydratorStrategyPluginManager = $sm->get(HydratorStrategyPluginManager::class);

        $config = $container->getServiceLocator()->get('config');
        $config = $config['migration'];

        return new MigrationTableGateway(
            $adapter,
            $metadata,
            $hydratorStrategyPluginManager,
            $config['table_name'],
            $slave
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, 'Core42\Migration');
    }
}
