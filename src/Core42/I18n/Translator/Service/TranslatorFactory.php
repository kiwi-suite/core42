<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\I18n\Translator\Service;

use Zend\Mvc\Service\TranslatorServiceFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorFactory extends TranslatorServiceFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Mvc\I18n\Translator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = parent::createService($serviceLocator);

        $translator->getTranslator()->setPluginManager(
            $serviceLocator->get('MvcTranslatorPluginManager')
        );

        return $translator;
    }
}
