<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper\Form;

use Zend\Form\Fieldset;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;

class FormRender extends AbstractHelper
{
    /**
     * @var FieldsetInterface
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
     * @var array
     */
    protected $params = array();

    /**
     * @param FieldsetInterface $form
     * @param null|string $action
     * @param null|array $params
     * @return $this
     */
    public function __invoke(FieldsetInterface $form = null, $action = null, array $params = null)
    {
        if ($form !== null) {
            $this->setForm($form);
        }

        if ($action !== null) {
            $this->setAction($action);
        }

        if ($params !== null) {
            $this->setParams($params);
        }

        return $this;
    }

    /**
     * @param FieldsetInterface $form
     * @return $this
     */
    public function setForm(FieldsetInterface $form)
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
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        try {

            /** @var FormElementRender $formElementRender */
            $formElementRender = $this->getView()->plugin('formElementRender');

            $elementHtml = array();
            foreach ($this->form as $element) {
                if ($element instanceof FieldsetInterface) {
                    $type = 'fieldset';
                } else {
                    $type = $element->getAttribute('type');
                }

                $elementPartial = (isset($this->partialMap[$element->getName()]))
                    ? $this->partialMap[$element->getName()]
                    : $this->getElementTemplate($type);

                $formElementRender->setElement($element);
                $formElementRender->setPartial($elementPartial);

                $elementHtml[] = $formElementRender->render();
            }

            if (empty($this->partial)) {
                $this->partial = $this->getFormLayout();
            }

            if ($this->form instanceof FormInterface) {
                $partialHelper = $this->view->plugin('partial');
                $html = $partialHelper($this->partial, array(
                    'form'          => $this->form,
                    'elements'      => $elementHtml,
                    'action'        => $this->action,
                    'hasErrors'     => count($this->form->getMessages()) > 0,
                    'params'        => $this->params
                ));
            } else {
                $html = implode(PHP_EOL, $elementHtml);
            }

            $this->partial = null;
            $this->partialMap = null;
            $this->themeName = null;
            $this->form = null;
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
