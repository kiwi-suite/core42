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


namespace Core42\Log\Service\Handler;

use Interop\Container\ContainerInterface;
use Monolog\Handler\NullHandler;
use Zend\ServiceManager\Factory\FactoryInterface;

class NullHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NullHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!empty($options['level'])) {
            return new NullHandler($options['level']);
        } else {
            return new NullHandler();
        }
    }
}
