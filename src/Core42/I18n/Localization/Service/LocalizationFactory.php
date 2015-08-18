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
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocalizationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['i18n'];
        /** @var Request $request */
        $request = $serviceLocator->get("request");
        $header = ($request instanceof Request) ? $request->getHeader("HTTP_ACCEPT_LANGUAGE", "") : '';

        return new Localization($header, $config);
    }
}
