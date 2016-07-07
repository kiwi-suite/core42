<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway\Service;

use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Core42\TableGateway\MigrationTableGateway;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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
        $adapter = $container->get('Db\Master');
        $slave = null;
        if ($container->has('Db\Slave')) {
            $slave = $container->get('Db\Slave');
        }

        $hydratorStrategyPluginManager = $container->get(HydratorStrategyPluginManager::class);

        $config = $container->get('config');
        $config = $config['migration'];

        $gateway = new MigrationTableGateway(
            $adapter,
            $hydratorStrategyPluginManager,
            $config['table_name'],
            $slave
        );

        $gateway->initialize();

        return $gateway;
    }
}
