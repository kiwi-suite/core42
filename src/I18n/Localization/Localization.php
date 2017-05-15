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


namespace Core42\I18n\Localization;

use Zend\Stdlib\AbstractOptions;

class Localization extends AbstractOptions
{
    const TYPE_LANGUAGE = 'language';
    const TYPE_REGION = 'region';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var string
     */
    protected $activeLocale;

    /**
     * @var string
     */
    protected $header;

    /**
     * @param array|null|\Traversable $header
     * @param null $options
     */
    public function __construct($header, $options = null)
    {
        $this->header = $header;
        parent::__construct($options);
    }

    /**
     * @param $type
     * @throws \Exception
     */
    public function setType($type)
    {
        if (!\in_array($type, [self::TYPE_LANGUAGE, self::TYPE_REGION])) {
            throw new \Exception('invalid type');
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $locales
     */
    public function setLocales(array $locales)
    {
        $this->locales = \array_filter($locales);
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return \array_keys($this->locales);
    }

    /**
     * @param $locale
     * @return string
     */
    public function getAvailableLocaleDisplay($locale)
    {
        $options = $this->getLocaleOptions($locale);
        if ($options !== false && \array_key_exists('name', $options)) {
            $name = $options['name'];
        } elseif ($options !== false) {
            if ($this->type == self::TYPE_REGION) {
                $name = \Locale::getDisplayRegion($locale);
            } else {
                $name = \Locale::getDisplayLanguage($locale);
            }
        } else {
            $name = '';
        }

        return $name;
    }

    /**
     * @return array
     */
    public function getAvailableLocalesDisplay()
    {
        $locales = $this->getAvailableLocales();

        $result = [];

        foreach ($locales as $_locale) {
            $result[$_locale] = $this->getAvailableLocaleDisplay($_locale);
        }

        return $result;
    }

    /**
     * @param string $lang
     * @return false|string
     */
    public function getAvailableLocaleByLanguage($lang)
    {
        $possibleLocales = [];

        foreach ($this->locales as $_locale => $options) {
            if (\array_key_exists('lang', $options)) {
                if ($options['lang'] == $lang) {
                    $possibleLocales[] = $_locale;
                }

                continue;
            }

            if ($lang == \Locale::getPrimaryLanguage($_locale)) {
                $possibleLocales[] = $_locale;
            }
        }

        if (empty($possibleLocales)) {
            return false;
        }

        if (\count($possibleLocales) == 1) {
            return \current($possibleLocales);
        }

        foreach ($possibleLocales as $_locale) {
            $options = $this->getLocaleOptions($_locale);
            if ($options === false) {
                continue;
            }

            if (\is_array($options) && \array_key_exists('default', $options) && $options['default'] === true) {
                return $_locale;
            }
        }

        return \current($possibleLocales);
    }

    /**
     * @param $locale
     * @return bool|array
     */
    public function getLocaleOptions($locale)
    {
        if (!\array_key_exists($locale, $this->locales)) {
            return false;
        }

        return $this->locales[$locale];
    }

    /**
     * @return bool
     */
    public function hasActiveLocale()
    {
        return $this->activeLocale !== null;
    }

    /**
     * @return string|bool
     */
    public function getActiveLocale()
    {
        if (!$this->hasActiveLocale()) {
            return false;
        }

        return $this->activeLocale;
    }

    /**
     * @return bool|string
     */
    public function getActiveLanguage()
    {
        if (!$this->hasActiveLocale()) {
            return false;
        }

        return \Locale::getPrimaryLanguage($this->activeLocale);
    }

    /**
     * @param string $locale
     */
    public function acceptLocale($locale)
    {
        \Locale::setDefault($locale);

        foreach (['.utf8', '.UTF-8', ''] as $encodigPrefix) {
            if ((\setlocale(LC_ALL, \Locale::canonicalize($locale) . $encodigPrefix)) !== false) {
                break;
            }
        }

        $this->activeLocale = $locale;
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function getDefaultLocale()
    {
        if (\count($this->locales) == 0) {
            throw new \Exception('no locales set');
        }

        foreach ($this->getAvailableLocales() as $_locale) {
            $options = $this->getLocaleOptions($_locale);
            if ($options === false) {
                continue;
            }

            if (\is_array($options) && \array_key_exists('default', $options) && $options['default'] === true) {
                return $_locale;
            }
        }

        return \current($this->getAvailableLocales());
    }

    /**
     * @return string
     */
    public function getLocaleFromHeader()
    {
        $locale = \Locale::acceptFromHttp($this->header);

        // fuzzy search for non fully qualitied locales (with language + region)
        $locale = str_replace('_', '-', $locale);
        foreach ($this->locales as $availableLocale => $options) {
            if(strpos($availableLocale, $locale) === 0) {
                $locale = $availableLocale;
            }
        }

        if (empty($locale) || !\in_array($locale, array_keys($this->locales))) {
            $locale = $this->getDefaultLocale();
        }

        return $locale;
    }
}
