<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
        if (!in_array($type, [self::TYPE_LANGUAGE, self::TYPE_REGION])) {
            throw new \Exception("invalid type");
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
        $this->locales = array_filter($locales);
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
        return array_keys($this->locales);
    }

    /**
     * @param $locale
     * @return string
     */
    public function getAvailableLocaleDisplay($locale)
    {
        $options = $this->getLocaleOptions($locale);
        if ($options !== false && array_key_exists("name", $options)) {
            $name = $options["name"];
        } elseif($options !== false) {
            switch ($this->type) {
                case self::TYPE_REGION;
                    $name = \Locale::getDisplayRegion($locale);
                    break;
                case self::TYPE_LANGUAGE:
                default:
                    $name = \Locale::getDisplayLanguage($locale);
                    break;
            }
        } else {
            $name = "";
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
            if (array_key_exists('lang', $options)) {
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

        if (count($possibleLocales) == 1) {
            return current($possibleLocales);
        }

        foreach ($possibleLocales as $_locale) {
            $options = $this->getLocaleOptions($_locale);
            if ($options === false) {
                continue;
            }

            if (is_array($options) && array_key_exists('default', $options) && $options['default'] === true) {
                return $_locale;
            }
        }

        return current($possibleLocales);
    }

    /**
     * @param $locale
     * @return bool|array
     */
    public function getLocaleOptions($locale)
    {
        if (!array_key_exists($locale, $this->locales)) {
            return false;
        }

        return $this->locales[$locale];
    }

    /**
     * @return bool
     */
    public function hasActiveLocale()
    {
        return ($this->activeLocale !== null);
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
        setlocale(LC_ALL, $locale);
        $this->activeLocale = $locale;
    }

    /**
     * @return string
     */
    public function getLocaleFromHeader()
    {
        $locale = \Locale::acceptFromHttp($this->header);

        if (empty($locale) || !in_array($locale, $this->locales)) {
            foreach ($this->locales as $_locale) {
                $options = $this->getLocaleOptions($_locale);
                if ($options === false) {
                    continue;
                }

                if (is_array($options) && array_key_exists('default', $options) && $options['default'] === true) {
                    $locale = $_locale;
                    break;
                }
            }
        }

        return $locale;
    }
}
