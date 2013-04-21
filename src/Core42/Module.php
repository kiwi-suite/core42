<?php
namespace Core42;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Core42\Command\AbstractCommand;
use Core42\Db\TableGateway\AbstractTableGateway;

class Module implements BootstrapListenerInterface,
                            ConfigProviderInterface,
                            AutoloaderProviderInterface
{
    /* 
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig ()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

	/* 
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap (\Zend\EventManager\EventInterface $e)
    {   
        AbstractCommand::setServiceManager($e->getApplication()->getServiceManager());
        AbstractTableGateway::setServiceManager($e->getApplication()->getServiceManager());
    }

	/* 
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php'
            ),
        );
    }
}
