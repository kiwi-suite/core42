<?php
namespace Core42\Command;

use Core42\ValueManager\ValueManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
     * @var \Core42\ValueManager\ValueManager
     */
    private $valueManager;

    /**
     * @var bool
     */
    private $dryRun = false;

    /**
     *
     */
    final public function __construct()
    {
        $this->valueManager = new ValueManager();

        $this->enableThrowExceptions(true);

        $this->init();
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
     *
     */
    protected function init() {}

    /**
     * @return \Core42\Command\AbstractCommand
     * @throws \Exception
     */
    final public function run()
    {
        try {
            $this->preExecute();

            if (!$this->hasCommandErrors() && $this->dryRun === false) {
                $this->execute();
                $this->postExecute();
            }
        } catch (\Exception $e) {
            $this->commandException = $e;
            if ($this->throwCommandExceptions === true) {
                throw $e;
            }
        }

        return $this;
    }

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
     * @return bool
     */
    final public function hasCommandErrors()
    {
        return $this->valueManager->hasErrors();
    }

    /**
     * @param  array                           $errors
     * @return \Core42\Command\AbstractCommand
     */
    final public function setCommandErrors(array $errors)
    {
        $this->valueManager->setErrors($errors);

        return $this;
    }

    /**
     * @param  string                          $name
     * @param  string                          $error
     * @return \Core42\Command\AbstractCommand
     */
    final public function setCommandError($name, $error)
    {
        $this->valueManager->setError($name, $error);

        return $this;
    }

    /**
     * @return ValueManager
     */
    final public function getValueManager()
    {
        return $this->valueManager;
    }

    /**
     * @return \Exception|null
     */
    final public function getException()
    {
        return $this->commandException;
    }
}
