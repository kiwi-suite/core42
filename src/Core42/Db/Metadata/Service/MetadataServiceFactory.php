<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Metadata\Service;

use Core42\Db\Metadata\CacheMetadata;
use Core42\Db\Metadata\Metadata;
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

        $config = $serviceLocator->get('Config');
        $cache = null;
        if (array_key_exists('metadata', $config)
            && array_key_exists('cache', $config['metadata'])
            && !empty($config['metadata']['cache'])
        ) {
            $cache = $serviceLocator->get($config['metadata']['cache']);
        }

        if (!empty($cache)) {
            return new CacheMetadata($adapter, $cache);
        }

        return new Metadata($adapter);
    }
}