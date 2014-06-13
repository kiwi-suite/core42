<?php
namespace Core42\Db\SelectQuery\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SelectQueryFallbackAbstractFactory implements AbstractFactoryInterface
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

        $adapter = $serviceLocator->getServiceLocator()->get('Db\Master');
        if ( $serviceLocator->getServiceLocator()->has('Db\Slave')) {
            $adapter =  $serviceLocator->getServiceLocator()->get('Db\Slave');
        }

        $hydratorStrategyPluginManager = $serviceLocator->getServiceLocator()->get('Core42\Hydrator\Strategy\Database\\' . $adapter->getPlatform()->getName() . '\PluginManager');

        return new $fqcn($adapter, $hydratorStrategyPluginManager);
    }

    protected function getFQCN($name)
    {
        if (strpos($name, '\\') === false) {
            return false;
        }

        $parts = explode('\\', $name, 2);

        return '\\' . $parts[0] . '\\SelectQuery\\' .$parts[1] . 'SelectQuery';
    }
}
