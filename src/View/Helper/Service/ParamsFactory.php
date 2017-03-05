<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\View\Helper\Service;

use Core42\View\Helper\Params;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ParamsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Params
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Params(
            $container->get('Request'),
            $container->get('Application')->getMvcEvent()->getRouteMatch()
        );
    }
}
