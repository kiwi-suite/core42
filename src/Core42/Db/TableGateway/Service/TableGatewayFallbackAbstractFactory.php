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
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TableGatewayFallbackAbstractFactory implements AbstractFactoryInterface
{

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $fqcn = $this->getFQCN($requestedName);
        if ($fqcn === false) {
            return false;
        }

        return class_exists($fqcn);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $fqcn = $this->getFQCN($requestedName);

        /* @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = $serviceLocator->getServiceLocator()->get('Db\Master');
        $slave = null;
        if ($serviceLocator->getServiceLocator()->has('Db\Slave')) {
            $slave =  $serviceLocator->getServiceLocator()->get('Db\Slave');
        }

        $sm = $serviceLocator->getServiceLocator();
        $hydratorStrategyPluginManager = $sm->get(HydratorStrategyPluginManager::class);

        /** @var AbstractTableGateway $gateway */
        $gateway = new $fqcn($adapter, $hydratorStrategyPluginManager, $slave);

        if ($gateway->getUseMetaDataFeature() === true) {
            $metadata = $serviceLocator->getServiceLocator()->get(Metadata::class);
            $gateway->enableMetadata($metadata, $hydratorStrategyPluginManager);
        }

        $gateway->initialize();

        return $gateway;
    }

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
}
