<?php
namespace Core42;

use Core42\Command\Service\CommandPluginManager;
use Core42\Session\SessionInitializer;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;

class Module implements BootstrapListenerInterface,
                            ConfigProviderInterface,
                            AutoloaderProviderInterface,
                            InitProviderInterface
{
    /*
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../../config/module.config.php',
            include __DIR__ . '/../../config/service.config.php',
            include __DIR__ . '/../../config/database.config.php',
            include __DIR__ . '/../../config/session.config.php',
            include __DIR__ . '/../../config/log.config.php',
            include __DIR__ . '/../../config/caches.config.php'
        );
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

        $aclConfig = $e->getApplication()->getServiceManager()->get('Core42\AclConfig');
        if (php_sapi_name() !== 'cli' && !empty($aclConfig) && !empty($aclConfig['guards'])) {
            foreach ($aclConfig['guards'] as $guard => $options) {
                if (!$e->getApplication()->getServiceManager()->has($guard)) {
                    continue;
                }
                $guard = $e->getApplication()->getServiceManager()->get($guard);
                $guard->setOptions($options);
                $e->getTarget()->getEventManager()->attach($guard);
            }
        }
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
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    private function addPeeringServiceManager(ServiceListenerInterface $serviceListener)
    {
        $serviceListener->addServiceManager(
            'Core42\CommandPluginManager',
            'commands',
            '\Core42\Command\Service\Feature\CommandProviderInterface',
            'getCommandConfig'
        );

        $serviceListener->addServiceManager(
            'Core42\TableGatewayPluginManager',
            'table_gateway',
            '\Core42\Db\TableGateway\Service\Feature\TableGatewayProviderInterface',
            'getTableGatewayConfig'
        );
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!($manager instanceof ModuleManager)) {
            return;
        }

        $this->addPeeringServiceManager($manager->getEvent()->getParam("ServiceManager")->get("ServiceListener"));
    }
}
