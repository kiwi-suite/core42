<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\I18n\Translator\Service;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorServiceFactory;

class TranslatorFactory extends TranslatorServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $translator = parent::__invoke($container, $requestedName, $options);

        if ($container->has('TranslatorPluginManager')) {
            $translator->setPluginManager($container->get('TranslatorPluginManager'));
        }

        if ($translator->isEventManagerEnabled()) {
            $config = $container->get('config');
            if (isset($config['translator']['missing_translations_handler'])) {
                $missingConfig = $config['translator']['missing_translations_handler'];
                if (isset($missingConfig['service']) && isset($missingConfig['action'])) {
                    $translator->getEventManager()->attach(
                        Translator::EVENT_MISSING_TRANSLATION,
                        [$container->get($missingConfig['service']), $missingConfig['action']]
                    );
                }
            }
        }

        return $translator;
    }
}
