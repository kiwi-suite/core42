<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Service;

use Core42\View\Helper\Localization;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LocalizationFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Localization
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Localization($container->get(\Core42\I18n\Localization\Localization::class));
    }
}
