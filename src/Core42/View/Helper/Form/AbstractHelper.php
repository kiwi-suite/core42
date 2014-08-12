<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form;

use Core42\Form\Theme\Theme;
use Core42\Form\Theme\ThemeManager;

class AbstractHelper extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * @var string
     */
    protected $themeName;

    /**
     * @return ThemeManager
     */
    public function getThemeManager()
    {
        return $this->themeManager;
    }

    /**
     * @param ThemeManager $themeManager
     * @return $this
     */
    public function setThemeManager($themeManager)
    {
        $this->themeManager = $themeManager;

        return $this;
    }

    /**
     * @param string $themeName
     * @return $this
     */
    public function setThemeName($themeName)
    {
        $this->themeName = $themeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        if (empty($this->themeName)) {
            return $this->getThemeManager()->getDefaultTheme();
        }

        return $this->getThemeManager()->getTheme($this->getThemeName());
    }
}
