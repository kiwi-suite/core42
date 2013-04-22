<?php
namespace Core42\Command;

use Zend\ServiceManager\ServiceManager;
use Core42\ServiceManager\ServiceManagerStaticAwareInterface;

abstract class AbstractCommand implements ServiceManagerStaticAwareInterface
{
    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

    public static function createCommand()
    {
        $className = get_called_class();
        return new $className;
    }

    final protected function __construct()
    {
        $this->init();
    }

    /**
     *
     * @param ServiceManager $manager
     */
    public static function setServiceManager(ServiceManager $manager)
    {
        self::$serviceManager = $manager;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     *
     */
    protected function init(){}

    /**
     *
     * @return \Core42\Command\AbstractCommand
     */
    final public function run()
    {
        $this->validate();
        $this->preExecute();
        $this->execute();
        $this->postExecute();

        return $this;
    }

    /**
     *
     */
    protected function validate(){}

    /**
     *
     */
    protected function preExecute(){}

    /**
     *
     */
    abstract protected function execute();

    /**
     *
     */
    protected function postExecute(){}
}
