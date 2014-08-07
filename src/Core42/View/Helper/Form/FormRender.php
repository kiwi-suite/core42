<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form;

use Zend\Form\FormInterface;

class FormRender extends AbstractHelper
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $partial;

    /**
     * @var array
     */
    protected $partialMap = array();

    /**
     * @param FormInterface $form
     * @param null|string $action
     * @param null|string $partial
     * @return $this
     */
    public function __invoke(FormInterface $form = null, $action = null, $partial = null)
    {
        if ($form !== null) {
            $this->setForm($form);
        }

        if ($action !== null) {
            $this->setAction($action);
        }

        if ($partial !== null) {
            $this->setPartial($partial);
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

        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = (string)$action;

        return $this;
    }

    /**
     * @param $partial
     * @return $this
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @param array $partialMap
     * @return $this
     */
    public function setPartialMap(array $partialMap)
    {
        $this->partialMap = $partialMap;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = "";

        try {

            /** @var FormElementRender $formElementRender */
            $formElementRender = $this->getView()->plugin('formElementRender');

            $theme =  $this->getTheme();

            $elementHtml = array();
            foreach ($this->form as $element) {
                $type = $element->getAttribute('type');
                $elementPartial = $theme->getElementTemplate($type);

                $formElementRender->setElement($element);
                $formElementRender->setPartial($elementPartial);

                $elementHtml[] = $formElementRender->render();
            }

            if (empty($this->partial)) {
                $this->partial = $theme->getFormLayout();
            }

            $partialHelper = $this->view->plugin('partial');
            $html = $partialHelper($this->partial, array(
                'form'          => $this->form,
                'elements'      => $elementHtml,
                'action'        => $this->action,
                'hasErrors'     => count($this->form->getMessages()) > 0
            ));

            $this->partial = null;
            $this->partialMap = null;
            $this->themeName = null;
            $this->form = null;
        } catch (\Exception $e) {

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
