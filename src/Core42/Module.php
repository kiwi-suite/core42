<?php
namespace Core42;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

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
        $config = $e->getApplication()->getServiceManager()->get("Config");
        
        if (!empty($config["service_manager_static_aware"])) {
            foreach ($config["service_manager_static_aware"] as $_class) {
                if (!is_callable($_class."::setServiceManager")) {
                    throw new \Exception("{$_class} doesn't implement ServiceManagerStaticAwareInterface");
                }
                call_user_func($_class."::setServiceManager", $e->getApplication()->getServiceManager());
            }
        }
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
