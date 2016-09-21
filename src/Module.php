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
use Core42\ModuleManager\Feature\CliConfigProviderInterface;
use Core42\ModuleManager\GetConfigTrait;
use Core42\Mvc\Environment\Environment;
use Core42\Mvc\Router\Http\AngularSegment;
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
use Zend\Stdlib\ArrayUtils;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface,
    InitProviderInterface,
    CliConfigProviderInterface
{
    use GetConfigTrait;

    const ENVIRONMENT_CLI = 'cli';
    const ENVIRONMENT_DEVELOPMENT = 'development';
    /**
     * @param \Zend\EventManager\EventInterface $e
     * @return array|void
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {

        if (Console::isConsole()) {
            return;
        }

        $e->getApplication()
            ->getServiceManager()
            ->get('RoutePluginManager')
            ->setFactory(AngularSegment::class, RouteInvokableFactory::class);
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $serviceManager = $manager->getEvent()->getParam('ServiceManager');
        if ($serviceManager->get(Environment::class)->is(self::ENVIRONMENT_CLI)) {
            $manager->getEventManager()->attach(
                ModuleEvent::EVENT_LOAD_MODULES_POST,
                [$this, 'addCliConfig'],
                PHP_INT_MAX
            );
        }
        $manager->getEventManager()->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
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

        unset($config['service_manager']['factories']['Zend\Db\Adapter\Adapter']);

        $configListener->setMergedConfig($config);
    }

    public function addCliConfig(ModuleEvent $e)
    {
        $cliConfig = [];

        foreach ($e->getTarget()->getLoadedModules() as $module) {
            if (!($module instanceof CliConfigProviderInterface)) {
                continue;
            }

            $moduleConfig = $module->getCliConfig();
            if (!is_array($moduleConfig)) {
                continue;
            }

            $cliConfig = ArrayUtils::merge($cliConfig, $moduleConfig);
        }

        if (empty($cliConfig)) {
            return;
        }
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        $config = ArrayUtils::merge($config, $cliConfig);
        $configListener->setMergedConfig($config);
    }

    /**
     * @return mixed
     */
    public function getCliConfig()
    {
        return include_once __DIR__ . '/../config/cli/cli.config.php';
    }
}
