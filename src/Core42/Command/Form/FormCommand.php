<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Form;

use Core42\Command\AbstractCommand;
use Zend\Form\FormInterface;

class FormCommand extends AbstractCommand
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var AbstractCommand
     */
    private $cmd;

    /**
     * @var callable
     */
    private $valueCallback;

    /**
     * @var bool
     */
    private $automaticFormFill = true;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $protectedData = [];

    /**
     * @var bool
     */
    private $takeOriginalData = false;

    /**
     *
     */
    protected function init()
    {
        $this->valueCallback = function ($values) {
            return $values;
        };
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
     * @param AbstractCommand $cmd
     * @return $this
     */
    public function setCommand(AbstractCommand $cmd)
    {
        $this->cmd = $cmd;

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setValueCallback($callback)
    {
        $this->valueCallback = $callback;

        return $this;
    }

    /**
     * @param bool $formFill
     * @return $this
     */
    public function enableAutomaticFormFill($formFill)
    {
        $this->automaticFormFill = (bool) $formFill;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param bool $takeOriginalData
     * @return $this
     */
    public function setTableOriginalData($takeOriginalData)
    {
        $this->takeOriginalData = $takeOriginalData;

        return $this;
    }

    /**
     * @param array $protectedData
     * @return $this
     */
    public function setProtectedData(array $protectedData)
    {
        $this->protectedData = $protectedData;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        if (empty($this->data)) {
            $this->data = $this->getServiceManager()->get('request')->getPost()->toArray();
        }

        foreach ($this->protectedData as $protName) {
            unset($this->data[$protName]);
        }

        if ($this->automaticFormFill ===  true) {
            $this->form->setData($this->data);
        }

        if (!$this->form->isValid()) {
            $errors = $this->form->getInputFilter()->getMessages();
            $this->addErrors($errors);
        }

        if ($this->takeOriginalData === true) {
            $values = call_user_func($this->valueCallback, $this->data);
        } else {
            $values = call_user_func($this->valueCallback, $this->form->getInputFilter()->getValues());
        }

        $this->cmd->hydrate($values);

        $this->cmd->configure();
        $this->cmd->preExecute();

        if ($this->cmd->hasErrors()) {
            $this->addErrors($this->cmd->getErrors());
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $result = $this->cmd->execute();
        $this->cmd->postExecute();

        return $result;
    }

    /**
     *
     */
    protected function shutdown()
    {
        $this->cmd->shutdown();

        if (!$this->hasErrors()) {
            return;
        }

        $errors = $this->getErrors();
        foreach ($errors as $elementName => $_errorList) {
            if (!$this->form->has($elementName)) {
                continue;
            }

            $this->form->get($elementName)->setMessages($_errorList);
        }
    }
}
