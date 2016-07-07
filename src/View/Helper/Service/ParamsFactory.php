<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
