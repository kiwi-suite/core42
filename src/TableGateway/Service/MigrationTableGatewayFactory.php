<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway\Service;

use Core42\Hydrator\BaseHydrator;
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

        $hydrator = $container->get('HydratorManager')->get(BaseHydrator::class);

        $config = $container->get('config');
        $config = $config['migration'];

        $gateway = new MigrationTableGateway(
            $adapter,
            $hydrator,
            $config['table_name'],
            $slave
        );

        $gateway->initialize();

        return $gateway;
    }
}
