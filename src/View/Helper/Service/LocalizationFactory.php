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

use Core42\View\Helper\Proxy;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LocalizationFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Proxy
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Proxy($container->get(\Core42\I18n\Localization\Localization::class));
    }
}
