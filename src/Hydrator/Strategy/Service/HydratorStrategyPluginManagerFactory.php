<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Service;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydratorStrategyPluginManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return HydratorStrategyPluginManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Adapter $adapter */
        $adapter = $container->get('Db\Master');

        $platform = strtolower($adapter->getPlatform()->getName());

        $config = $container->get('config');
        $config = (array_key_exists('hydrator_strategy', $config)) ? $config['hydrator_strategy'] : [];
        $config = (array_key_exists($platform, $config)) ? $config[$platform] : [];
        
        $manager = new HydratorStrategyPluginManager(new Config($config));
        $manager->setServiceLocator($container);

        return $manager;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, HydratorStrategyPluginManager::class);
    }
}
