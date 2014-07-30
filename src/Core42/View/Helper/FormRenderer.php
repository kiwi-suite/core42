<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\Form\FormInterface;
use Zend\View\Helper\AbstractHelper;

class FormRenderer extends AbstractHelper
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function __invoke(FormInterface $form = null)
    {
        if ($form !== null) {
            $this->setForm($form);
        }

        return $this;
    }

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;
        $this->form->prepare();

        return $this;
    }

    /**
     * @param $element
     * @param array $additionalParams
     * @param null $partial
     * @return mixed
     */
    public function renderElement($element, $additionalParams = array(), $partial = null)
    {
        if (is_string($element)) {
            $element = $this->form->get($element);
        }

        if ($partial === null) {
            $partial = 'partial/form/text';
        }

        $partialHelper = $this->view->plugin('partial');

        $model = $additionalParams;
        $model['element'] = $element;

        return $partialHelper($partial, $model);
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = array();

        foreach ($this->form as $element) {
            $html[] = $this->renderElement($element);
        }

        return implode(PHP_EOL, $html);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return "";
        }
    }
}
