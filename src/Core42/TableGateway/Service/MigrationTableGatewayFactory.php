<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway\Service;

use Core42\TableGateway\MigrationTableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MigrationTableGatewayFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->getServiceLocator()->get('Db\Master');
        $slave = null;
        if ($serviceLocator->getServiceLocator()->has('Db\Slave')) {
            $slave = $serviceLocator->getServiceLocator()->get('Db\Slave');
        }
        $metadata = $serviceLocator->getServiceLocator()->get('Metadata');

        $sm = $serviceLocator->getServiceLocator();
        $hydratorStrategyPluginManager = $sm->get('Core42\HydratorStrategyPluginManager');

        $config = $serviceLocator->getServiceLocator()->get('config');
        $config = $config['migration'];

        return new MigrationTableGateway(
            $adapter,
            $metadata,
            $hydratorStrategyPluginManager,
            $config['table_name'],
            $slave
        );
    }
}
