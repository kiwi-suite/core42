<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form;

class AbstractHelper extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @var string
     */
    protected $formLayout;

    /**
     * @var array
     */
    protected $elementTemplateMap = [];

    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if (!isset($config['layout_template'])) {
            throw new \Exception("'layout_template' not set");
        }
        $this->setFormLayout($config['layout_template']);

        if (!isset($config['element_template_map'])) {
            throw new \Exception("'element_template_map' not set");
        }
        $this->setElementTemplateMap($config['element_template_map']);
    }

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
