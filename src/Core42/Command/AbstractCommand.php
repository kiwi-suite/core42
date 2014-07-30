<?php
namespace Core42\Command;

use Zend\Console\ColorInterface;
use Zend\Console\Console;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractCommand implements CommandInterface, ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var \Exception|null
     */
    protected $commandException;

    /**
     * @var bool
     */
    private $throwCommandExceptions = true;

    /**
     * @var bool
     */
    private $autoValidateForm = true;

    /**
     * @var bool
     */
    private $dryRun = false;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var array
     */
    private $errors = array();

    /**
     *
     */
    final public function __construct()
    {
        $this->enableThrowExceptions(true);
    }

    /**
     * @param boolean $dryRun
     * @return \Core42\Command\AbstractCommand
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = (boolean) $dryRun;

        return $this;
    }

    /**
     * @param  bool                            $enable
     * @return \Core42\Command\AbstractCommand
     */
    final public function enableThrowExceptions($enable)
    {
        $this->throwCommandExceptions = (boolean) $enable;

        return $this;
    }

    /**
     * @param FormInterface $form
     * @param boolean $autoValidateForm
     * @return $this
     */
    public function setForm(FormInterface $form, $autoValidateForm = true)
    {
        $this->form = $form;

        $this->autoValidateForm = $autoValidateForm;

        return $this;
    }

    /**
     * @return FormInterface
     * @throws \Exception
     */
    public function getForm()
    {
        if (!$this->hasForm()) {
            throw new \Exception('form not set');
        }

        return $this->form;
    }

    /**
     * @return bool
     */
    public function hasForm()
    {
        return ($this->form !== null);
    }

    /**
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        return $this->getForm()->getInputFilter();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator()->getServiceLocator();
    }

    /**
     * @return \Core42\Command\AbstractCommand
     * @throws \Exception
     */
    final public function run()
    {
        $this->configure();

        try {
            if ($this->hasForm() && $this->autoValidateForm === true) {
                $this->validateForm();
            }

            if ($this->hasForm()) {
                $this->extractForm();
            }

            $this->preExecute();

            if (!$this->hasErrors() && $this->dryRun === false) {
                $this->execute();
                $this->postExecute();
            }
        } catch (\Exception $e) {
            $this->commandException = $e;
            $this->shutdown();
            if ($this->throwCommandExceptions === true) {
                throw $e;
            }
        }

        //TODO might be more clean with PHP5.5 finally
        if ($this->commandException !== null) {
            $this->shutdown();
        }

        return $this;
    }

    /**
     *
     */
    protected function configure() {}

    /**
     *
     */
    protected function validateForm()
    {
        if (!$this->getForm()->isValid()) {
            $this->addErrors($this->getForm()->getMessages(), false);
        }

        $classMethodHydrator = new ClassMethods(false);
        $classMethodHydrator->hydrate($this->getForm()->getData(), $this);
    }

    /**
     *
     */
    protected function extractForm() {}

    /**
     *
     */
    protected function preExecute() {}

    /**
     *
     */
    abstract protected function execute();

    /**
     *
     */
    protected function postExecute() {}

    /**
     *
     */
    protected function shutdown() {}

    /**
     * @param string $name
     * @param string $message
     * @param bool $propagateForm
     * @return $this
     */
    protected function addError($name, $message, $propagateForm = true)
    {
        if ($this->hasForm() && $propagateForm === true && $this->getForm()->has($name)) {
            $formMessages = $this->getForm()->get($name)->getMessages();
            $formMessages[] = $message;
            $this->getForm()->get($name)->setMessages($formMessages);
        }

        if (!array_key_exists($name, $this->errors)) {
            $this->errors[$name] = array();
        }

        $this->errors[$name][] = $message;

        return $this;
    }

    /**
     * @param array $errors
     * @param boolean $propagateForm
     * @return $this
     */
    protected function addErrors(array $errors, $propagateForm = true)
    {
        foreach ($errors as $name => $listErrors) {
            foreach ($listErrors as $message) {
                $this->addError($name, $message, $propagateForm);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (count($this->errors) > 0);
    }

    /**
     * @return \Exception|null
     */
    final public function getException()
    {
        return $this->commandException;
    }

    /**
     * @param string $message
     */
    protected function consoleOutput($message)
    {
        if (!Console::isConsole()) {
            return;
        }

        $message = preg_replace_callback('#(\\\\?)<(/?)([a-z][a-z0-9_=;-]*)?>((?: [^<\\\\]+ | (?!<(?:/?[a-z]|/>)). | .(?<=\\\\<) )*)#isx', function ($matches) {
            if ($matches[2] == '/') {
                return $matches[4];
            }

            switch ($matches[3]) {
                case 'error':
                    return Console::getInstance()->colorize($matches[4], ColorInterface::WHITE, ColorInterface::RED);
                case 'info':
                    return Console::getInstance()->colorize($matches[4], ColorInterface::GREEN);
                case 'comment':
                    return Console::getInstance()->colorize($matches[4], ColorInterface::YELLOW);
                case 'question':
                    return Console::getInstance()->colorize($matches[4], ColorInterface::BLACK, ColorInterface::CYAN);
                default:
                    return $matches[4];
            }
        }, $message);

        Console::getInstance()->writeLine($message);
    }
}
