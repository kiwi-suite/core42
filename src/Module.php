<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
use Zend\Stdlib\Glob;

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
        $config = $configListener->getMergedConfig(false);

        $abstractFactories = $config['service_manager']['abstract_factories'];
        foreach ($abstractFactories as $key => $class) {
            if (in_array($class, [
                AdapterAbstractServiceFactory::class,
                FormAbstractServiceFactory::class,
                ContainerAbstractServiceFactory::class,
                InputFilterAbstractServiceFactory::class,
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
        $config = $configListener->getMergedConfig(false);
        $config = ArrayUtils::merge($config, $cliConfig);
        $configListener->setMergedConfig($config);
    }

    /**
     * @return mixed
     */
    public function getCliConfig()
    {
        $config = [];
        $configPath = dirname((new \ReflectionClass($this))->getFileName()) . '/../config/cli/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
