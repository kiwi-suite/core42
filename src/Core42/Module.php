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
            include __DIR__ . '/../../config/mail.config.php',
            include __DIR__ . '/../../config/caches.config.php',
            include __DIR__ . '/../../config/cli.config.php',
            include __DIR__ . '/../../config/migration.config.php',
            include __DIR__ . '/../../config/seeding.config.php',
            include __DIR__ . '/../../config/assets.config.php',
            include __DIR__ . '/../../config/permission.config.php'
        );
    }

    /*
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        $sessionInit = new SessionInitializer();
        $sessionInit->initialize($e->getApplication()->getServiceManager());

        $rbacConfig = $e->getApplication()->getServiceManager()->get('Core42\Permission\Config');
        if (!empty($rbacConfig) && $rbacConfig['enabled'] === true) {
            foreach ($rbacConfig['guards'] as $serviceName => $options) {
                /** @var $guard GuardInterface */
                $guard = $e->getApplication()->getServiceManager()->get($serviceName);
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

        $serviceListener->addServiceManager(
            'Core42\SelectQueryPluginManager',
            'select_query',
            '\Core42\Db\SelectQuery\Service\Feature\SelectQueryProviderInterface',
            'getSqlQueryConfig'
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
