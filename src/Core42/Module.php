<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42;

use Core42\Session\SessionInitializer;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    InitProviderInterface
{
    /**
     * @return array|\Traversable
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
            include __DIR__ . '/../../config/form.config.php'
        );
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     * @return array|void
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        $e->getApplication()->getServiceManager()->get('Zend\Session\Service\SessionManager');

        $e->getTarget()->getEventManager()->attach(
            $e->getApplication()->getServiceManager()->get('Core42\Permission\RedirectStrategy')
        );

        $e->getTarget()->getEventManager()->attach(
            $e->getApplication()->getServiceManager()->get('Core42\Permission\UnauthorizedStrategy')
        );
    }

    /**
     * @param ServiceListenerInterface $serviceListener
     */
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

        $serviceListener->addServiceManager(
            'Core42\QueueAdapterPluginManager',
            'queue_adapter',
            'Core42\Queue\Service\Feature\QueueAdapterProviderInterface',
            'getQueueAdapter'
        );

        $serviceListener->addServiceManager(
            'Core42\FormPluginManager',
            'forms',
            'Core42\Form\Service\Feature\FormProviderInterface',
            'getFormConfig'
        );

        $serviceListener->addServiceManager(
            'Core42\HydratorStrategyPluginManager',
            'hydrator_strategy',
            'Core42\Hydrator\Database\DatabaseStrategyInterface',
            'getHydratorStrategy'
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
