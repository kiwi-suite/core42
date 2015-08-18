<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Localization extends AbstractHelper
{
    /**
     * @var \Core42\I18n\Localization\Localization
     */
    protected $localization;

    /**
     * @param \Core42\I18n\Localization\Localization $localization
     */
    public function __construct(\Core42\I18n\Localization\Localization $localization)
    {
        $this->localization = $localization;
    }

    /**
     *
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments = [])
    {
        return call_user_func_array([$this->localization, $method], $arguments);
    }
}
