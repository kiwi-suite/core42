<?php
namespace Core42\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormElementRender extends AbstractHelper
{
    private $element;

    private $partial;

    public function __invoke(ElementInterface $element, $partial = null)
    {
        $this->element = $element;

        $this->partial = $partial;

        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $html = "";
        try {
            $partialHelper = $this->view->plugin('partial');

            $partialFile = $this->partial;
            if (empty($partialFile)) {
                //TODO lookup for the right partial
            }

            $model = array(
                'element' => $this->element
            );

            $html = $partialHelper($partialFile, $model);
        } catch (\Exception $e) {}

        return $html;
    }
}
