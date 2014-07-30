<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormElementRender extends AbstractHelper
{
    /**
     * @var ElementInterface
     */
    private $element;

    /**
     * @var string
     */
    private $partial;

    /**
     * @var array
     */
    private $extraParams = array();

    /**
     * @param ElementInterface $element
     * @param null $partial
     * @param array $extraParams
     * @return $this
     */
    public function __invoke(ElementInterface $element, $partial = null, $extraParams = array())
    {
        $this->element = $element;

        $this->partial = $partial;

        $this->extraParams = $extraParams;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = "";
        try {
            $partialHelper = $this->view->plugin('partial');

            $partialFile = $this->partial;
            if (empty($partialFile)) {
                //TODO lookup for the right partial
            }

            $model = $this->extraParams;
            $model['element'] = $this->element;

            $html = $partialHelper($partialFile, $model);
        } catch (\Exception $e) {

        }

        return $html;
    }
}
