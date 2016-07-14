<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42;

use Core42\Console\Console;
use Core42\Mvc\Router\Http\AngularSegment;
use Core42\Permission\Rbac\Strategy\RedirectStrategy;
use Core42\Permission\Rbac\Strategy\UnauthorizedStrategy;
use Zend\Db\Adapter\AdapterAbstractServiceFactory;
use Zend\Form\FormAbstractServiceFactory;
use Zend\InputFilter\InputFilterAbstractServiceFactory;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Router\RouteInvokableFactory;
use Zend\Session\Service\ContainerAbstractServiceFactory;

class Module implements
    BootstrapListenerInterface,
    InitProviderInterface,
    ConfigProviderInterface
{
    /**
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/service.config.php',
            include __DIR__ . '/../config/database.config.php',
            include __DIR__ . '/../config/session.config.php',
            include __DIR__ . '/../config/log.config.php',
            include __DIR__ . '/../config/mail.config.php',
            include __DIR__ . '/../config/caches.config.php',
            include __DIR__ . '/../config/cli.config.php',
            include __DIR__ . '/../config/migration.config.php',
            include __DIR__ . '/../config/assets.config.php',
            include __DIR__ . '/../config/permissions.config.php',
            include __DIR__ . '/../config/form.config.php',
            include __DIR__ . '/../config/i18n.config.php',
            include __DIR__ . '/../config/cron.config.php'
        );
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     * @return array|void
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        if (Console::isConsole()) {
            return;
        }
        $e->getApplication()->getServiceManager()->get('Zend\Session\Service\SessionManager');
        
        $e->getApplication()
            ->getServiceManager()
            ->get('RoutePluginManager')
            ->setFactory(AngularSegment::class, RouteInvokableFactory::class);
        
        $e->getApplication()
            ->getServiceManager()
            ->get(RedirectStrategy::class)
            ->attach($e->getTarget()->getEventManager());

        $e->getApplication()
            ->getServiceManager()
            ->get(UnauthorizedStrategy::class)
            ->attach($e->getTarget()->getEventManager());
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $events = $manager->getEventManager();

        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    /**
     * @param ModuleEvent $e
     */
    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);

        $abstractFactories = $config['service_manager']['abstract_factories'];
        foreach ($abstractFactories as $key => $class) {
            if (in_array($class, [
                AdapterAbstractServiceFactory::class,
                FormAbstractServiceFactory::class,
                ContainerAbstractServiceFactory::class,
                InputFilterAbstractServiceFactory::class
            ])) {
                unset($abstractFactories[$key]);
            }
        }

        $config['service_manager']['abstract_factories'] = array_values($abstractFactories);

        //unset($config['service_manager']['factories'][AdapterInterface::class]);
        unset($config['service_manager']['factories']['Zend\Db\Adapter\Adapter']);

        $configListener->setMergedConfig($config);
    }
}
