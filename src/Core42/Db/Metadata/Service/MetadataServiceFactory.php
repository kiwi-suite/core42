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
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetadataServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return CacheMetadata|Metadata
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get('Db\Master');
        if ($container->has('Db\Slave')) {
            $adapter = $container->get('Db\Slave');
        }

        $config = $container->get('Config');
        $cache = null;
        if (array_key_exists('metadata', $config)
            && array_key_exists('cache', $config['metadata'])
            && !empty($config['metadata']['cache'])
        ) {
            $cache = $container->get($config['metadata']['cache']);
        }

        if (!empty($cache)) {
            return new CacheMetadata($adapter, $cache);
        }

        return new Metadata($adapter);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Metadata::class);
    }
}
