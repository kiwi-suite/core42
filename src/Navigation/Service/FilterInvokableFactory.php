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

namespace Core42\Navigation\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for instantiating classes with no dependencies or which accept a single array.
 *
 * The InvokableFactory can be used for any class that:
 *
 * - has no constructor arguments;
 * - accepts a single array of arguments via the constructor.
 *
 * It replaces the "invokables" and "invokable class" functionality of the v2
 * service manager.
 */
final class FilterInvokableFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($options['container']);
    }
}
