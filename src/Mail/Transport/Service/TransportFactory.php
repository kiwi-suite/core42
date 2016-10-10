<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Mail\Transport\Service;

use Core42\Mail\Transport\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TransportFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \Swift_Transport
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container);

        return Factory::create($config);
    }

    /**
     * @param ContainerInterface $container
     * @return array
     * @throws \Exception
     */
    private function getConfig(ContainerInterface $container)
    {
        $config = $container->get('Config');

        if (isset($config['mail']['transport'])) {
            return $config['mail']['transport'];
        }

        throw new \Exception('mail transport config not provided');
    }
}
