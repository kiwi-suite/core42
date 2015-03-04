<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form;

use Zend\Form\ElementInterface;

class FormElementRender extends AbstractHelper
{
    /**
     * @var ElementInterface
     */
    protected $element;

    /**
     * @var string
     */
    protected $partial;

    /**
     * @param ElementInterface|null $element
     * @param string|null $partial
     * @return $this
     */
    public function __invoke(ElementInterface $element = null, $partial = null)
    {
        if ($element !== null) {
            $this->setElement($element);
        }

        if ($partial !== null) {
            $this->setPartial($partial);
        }

        return $this;
    }

    /**
     * @param ElementInterface $element
     * @return $this
     */
    public function setElement(ElementInterface $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * @param string $partial
     * @return $this
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = "";
        try {
            $partialHelper = $this->view->plugin('partial');

            if (empty($this->partial)) {
                $this->partial = $this->getElementTemplate($this->element->getAttribute('type'));
            }

            $html = $partialHelper($this->partial, array(
                'element'       => $this->element,
                'hasErrors'     => count($this->element->getMessages()) > 0
            ));

            $this->partial = null;
            $this->element = null;
            $this->themeName = null;
        } catch (\Exception $e) {
            $html = $e->getMessage();
        }

        return $html;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
