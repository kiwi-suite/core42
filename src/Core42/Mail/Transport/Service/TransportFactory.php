<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Mail\Transport\Service;

use Zend\Mail\Transport\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransportFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);

        return Factory::create($config);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws \Exception
     */
    private function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get("Config");

        if (isset($config['mail']['transport'])) {
            return $config['mail']['transport'];
        }

        throw new \Exception("mail transport config not provided");
    }
}
