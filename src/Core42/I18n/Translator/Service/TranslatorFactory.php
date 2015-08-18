<?php
/**
 * core42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\I18n\Translator\Service;

use Zend\I18n\Translator\Translator;
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

        if ($translator->isEventManagerEnabled()) {
            $config = $serviceLocator->get('Config');
            if (isset($config['translator']['missing_translations_handler'])) {
                $missingConfig = $config['translator']['missing_translations_handler'];
                if (isset($missingConfig['service']) && isset($missingConfig['action'])) {
                    $translator->getEventManager()->attach(
                        Translator::EVENT_MISSING_TRANSLATION,
                        [$serviceLocator->get($missingConfig['service']), $missingConfig['action']]
                    );
                }
            }
        }

        return $translator;
    }
}
