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


namespace Core42\I18n\Localization\Service;

use Core42\I18n\Localization\Localization;
use Interop\Container\ContainerInterface;
use Zend\Http\Header\Accept\FieldValuePart\LanguageFieldValuePart;
use Zend\Http\Header\AcceptLanguage;
use Zend\Http\PhpEnvironment\Request;
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
        $config = $container->get('config')['i18n'];
        /** @var Request $request */
        $request = $container->get('request');
        /** @var AcceptLanguage $header */
        $header = ($request instanceof Request) ? $request->getHeader('Accept-Language', '') : '';

        if($header instanceof AcceptLanguage) {
            /** @var LanguageFieldValuePart $languageFieldValue */
            $languageFieldValue = $header->getPrioritized()[0];
            $header = $languageFieldValue->getLanguage();
        }

        return new Localization($header, $config);
    }
}
