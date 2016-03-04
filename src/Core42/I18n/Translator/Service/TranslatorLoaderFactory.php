<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\I18n\Translator\Service;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\LoaderPluginManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorLoaderFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return LoaderPluginManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = (array_key_exists('translation_manager', $config)) ? $config['translation_manager'] : [];

        $manager = new LoaderPluginManager(new Config($config));
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
        return $this($serviceLocator, LoaderPluginManager::class);
    }
}
