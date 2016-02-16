<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\I18n\Localization\Service;

use Core42\I18n\Localization\Localization;
use Interop\Container\ContainerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        $config = $container->get('config')['i18n'];
        /** @var Request $request */
        $request = $container->get("request");
        $header = ($request instanceof Request) ? $request->getHeader("HTTP_ACCEPT_LANGUAGE", "") : '';

        return new Localization($header, $config);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Localization::class);
    }
}
