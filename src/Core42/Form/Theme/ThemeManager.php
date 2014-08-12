<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Form\Theme;

class ThemeManager
{
    /**
     * @var Theme[]
     */
    protected $themes = array();

    /**
     * @var string
     */
    protected $defaultThemeName;

    /**
     * @param array $config
     * @throws \Exception
     */
    public function factory(array $config)
    {
        foreach ($config as $name => $options) {
            $theme = new Theme();
            $theme->setName($name);

            if (!isset($options['layout_template'])) {
                throw new \Exception("'layout_template' not set");
            }
            $theme->setFormLayout($options['layout_template']);

            if (!isset($options['element_template_map'])) {
                throw new \Exception("'element_template_map' not set");
            }
            $theme->setElementTemplateMap($options['element_template_map']);

            $this->addTheme($theme);
        }
    }

    /**
     * @param Theme $theme
     */
    public function addTheme(Theme $theme)
    {
        $this->themes[$theme->getName()] = $theme;
    }

    /**
     * @param string $name
     * @throws \Exception
     * @return Theme
     */
    public function getTheme($name)
    {
        if (!isset($this->themes[$name])) {
            throw new \Exception("theme named '{$name}' not found'");
        }

        return $this->themes[$name];
    }

    /**
     * @param string $defaultThemeName
     */
    public function setDefaultThemeName($defaultThemeName)
    {
        $this->defaultThemeName = $defaultThemeName;
    }

    /**
     * @return Theme
     * @throws \Exception
     */
    public function getDefaultTheme()
    {
        return $this->getTheme($this->defaultThemeName);
    }
}
