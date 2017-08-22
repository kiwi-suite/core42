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


namespace Core42\Mvc\Controller\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ControllerFallbackAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param string $name
     * @return bool|string
     */
    protected function getFQCN($name)
    {
        if (\class_exists($name)) {
            return $name;
        }

        if (\mb_strpos($name, '\\') === false) {
            return false;
        }

        $parts = \explode('\\', $name, 2);

        return '\\' . $parts[0] . '\\Controller\\' . $parts[1] . 'Controller';
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $fqcn = $this->getFQCN($requestedName);
        if ($fqcn === false) {
            return false;
        }

        return \class_exists($fqcn);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fqcn = $this->getFQCN($requestedName);

        return new $fqcn($container);
    }
}
