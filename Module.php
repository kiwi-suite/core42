<?php
namespace Core42;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Core42\Application\Registry;

class Module implements BootstrapListenerInterface,
                            ConfigProviderInterface,
                            AutoloaderProviderInterface
{
    /* 
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

	/* 
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap (\Zend\EventManager\EventInterface $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, array($this, 'startupRegistry'), 100);
    }

	/* 
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function startupRegistry(MvcEvent $e)
    {
        Registry::setServiceManager($e->getApplication()->getServiceManager());
    }
}
