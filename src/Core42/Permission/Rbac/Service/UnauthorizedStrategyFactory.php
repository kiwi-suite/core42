<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Service;

use Core42\Permission\Rbac\Strategy\UnauthorizedStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UnauthorizedStrategyFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return UnauthorizedStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UnauthorizedStrategy($serviceLocator->get('Core42\Permission'));
    }
}
