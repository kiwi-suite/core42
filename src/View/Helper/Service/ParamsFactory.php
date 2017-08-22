<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
