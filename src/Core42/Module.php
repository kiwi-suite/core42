<?php
namespace Core42;

use Core42\Session\SessionInitializer;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements BootstrapListenerInterface,
                            ConfigProviderInterface,
                            AutoloaderProviderInterface,
                            ConsoleUsageProviderInterface
{
    /*
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /*
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
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

        $sessionInit = new SessionInitializer();
        $sessionInit->initialize($e->getApplication()->getServiceManager());
    }

    /*
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php'
            ),
        );
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
       return array(
            'migration-make name',
           array('name', 'name of the migration'),
       );
    }
}
