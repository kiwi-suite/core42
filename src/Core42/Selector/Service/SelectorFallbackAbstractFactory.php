<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Selector\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SelectorFallbackAbstractFactory implements AbstractFactoryInterface
{

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $fqcn = $this->getFQCN($requestedName);
        if ($fqcn === false) {
            return false;
        }

        return class_exists($fqcn);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $fqcn = $this->getFQCN($requestedName);

        return new $fqcn($serviceLocator->getServiceLocator());
    }

    /**
     * @param string $name
     * @return bool|string
     */
    protected function getFQCN($name)
    {
        if (class_exists($name)) {
            return $name;
        }

        if (strpos($name, '\\') === false) {
            return false;
        }

        $parts = explode('\\', $name, 2);

        return '\\' . $parts[0] . '\\Selector\\' .$parts[1] . 'Selector';
    }
}