<?php
namespace Core42\Db\Metadata\Service;

use Zend\Db\Metadata\Metadata;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetadataServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Db\Master');
        if ($serviceLocator->has('Db\Slave')) {
            $adapter = $serviceLocator->get('Db\Slave');
        }

        return new Metadata($adapter);
    }
}
