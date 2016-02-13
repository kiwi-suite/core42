<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Mail\Transport\Service;

use Interop\Container\ContainerInterface;
use Zend\Mail\Transport\Factory;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransportFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TransportInterface
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container);

        return Factory::create($config);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, TransportInterface::class);
    }

    /**
     * @param ContainerInterface $container
     * @return array
     * @throws \Exception
     */
    private function getConfig(ContainerInterface $container)
    {
        $config = $container->get("Config");

        if (isset($config['mail']['transport'])) {
            return $config['mail']['transport'];
        }

        throw new \Exception("mail transport config not provided");
    }
}
