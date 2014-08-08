<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Form\Theme;

class Theme
{
    /**
     * @var String
     */
    protected $name;

    /**
     * @var string
     */
    protected $formLayout;

    /**
     * @var array
     */
    protected $elementTemplateMap = array();

    /**
     * @return array
     */
    public function getElementTemplateMap()
    {
        return $this->elementTemplateMap;
    }

    /**
     * @param array $elementTemplateMap
     */
    public function setElementTemplateMap($elementTemplateMap)
    {
        $this->elementTemplateMap = $elementTemplateMap;
    }

    /**
     * @return string
     */
    public function getFormLayout()
    {
        return $this->formLayout;
    }

    /**
     * @param string $formLayout
     */
    public function setFormLayout($formLayout)
    {
        $this->formLayout = $formLayout;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $elementType
     * @throws \Exception
     * @return string
     */
    public function getElementTemplate($elementType)
    {
        if (!isset($this->elementTemplateMap[$elementType])) {
            throw new \Exception("'{$elementType}' not inside template map");
        }

        return $this->elementTemplateMap[$elementType];
    }
}
