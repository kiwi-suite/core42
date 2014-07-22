<?php
namespace Core42\Command;

use Zend\Form\FormInterface;

interface CommandInterface
{
    /**
     * @return mixed
     */
    public function run();

    /**
     * @param boolean $dryRun
     * @return CommandInterface
     */
    public function setDryRun($dryRun);

    /**
     * @param boolean $enable
     * @return CommandInterface
     */
    public function enableThrowExceptions($enable);

    /**
     * @param FormInterface $form
     * @return CommandInterface
     */
    public function setForm(FormInterface $form);

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return boolean
     */
    public function hasErrors();
}
