<?php
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
        $slave = $adapter;
        if ( $serviceLocator->getServiceLocator()->has('Db\Slave')) {
            $slave =  $serviceLocator->getServiceLocator()->get('Db\Slave');
        }
        $metadata = $serviceLocator->getServiceLocator()->get('Metadata');
        $hydratorStrategyPluginManager = $serviceLocator->getServiceLocator()->get('Core42\Hydrator\Strategy\Database\\' . $adapter->getPlatform()->getName() . '\PluginManager');

        $config = $serviceLocator->getServiceLocator()->get('config');
        $config = $config['migration'];

        return new MigrationTableGateway($adapter, $slave, $metadata, $hydratorStrategyPluginManager, $config['table_name']);
    }
}
