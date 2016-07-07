<?php
namespace Core42;

use Core42\Command\Console\ConsoleDispatcher;
use Core42\Command\Console\Service\ConsoleDispatcherFactory;
use Core42\Command\Service\CommandPluginManager;
use Core42\Command\Service\CommandPluginManagerFactory;
use Core42\Db\Adapter\Profiler\LoggingProfiler;
use Core42\Db\TableGateway\Service\TableGatewayPluginManager;
use Core42\Db\TableGateway\Service\TableGatewayPluginManagerFactory;
use Core42\Db\Transaction\Service\TransactionManagerFactory;
use Core42\Db\Transaction\TransactionManager;
use Core42\Form\Service\FormPluginManager;
use Core42\Form\Service\FormPluginManagerFactory;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManagerFactory;
use Core42\I18n\Localization\Localization;
use Core42\I18n\Localization\Service\LocalizationFactory;
use Core42\I18n\Translator\Service\TranslatorLoaderFactory;
use Core42\Mail\Transport\Service\TransportFactory;
use Core42\Mvc\TreeRouteMatcher\Service\TreeRouteMatcherFactory;
use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Core42\Navigation\Navigation;
use Core42\Navigation\Options\NavigationOptions;
use Core42\Navigation\Service\NavigationFactory;
use Core42\Navigation\Service\NavigationOptionsFactory;
use Core42\Permission\Rbac\Assertion\AssertionPluginManager;
use Core42\Permission\Rbac\Guard\GuardPluginManager;
use Core42\Permission\Rbac\Rbac;
use Core42\Permission\Rbac\RbacManager;
use Core42\Permission\Rbac\Role\RoleProviderPluginManager;
use Core42\Permission\Rbac\Service\AssertionPluginManagerFactory;
use Core42\Permission\Rbac\Service\GuardPluginManagerFactory;
use Core42\Permission\Rbac\Service\RbacFactory;
use Core42\Permission\Rbac\Service\RbacManagerFactory;
use Core42\Permission\Rbac\Service\RedirectStrategyFactory;
use Core42\Permission\Rbac\Service\RoleProviderPluginManagerFactory;
use Core42\Permission\Rbac\Service\UnauthorizedStrategyFactory;
use Core42\Permission\Rbac\Strategy\RedirectStrategy;
use Core42\Permission\Rbac\Strategy\UnauthorizedStrategy;
use Core42\Selector\Service\SelectorPluginManager;
use Core42\Selector\Service\SelectorPluginManagerFactory;
use Core42\TableGateway\Service\MigrationTableGatewayFactory;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Service\SessionConfigFactory;
use Zend\Session\Service\SessionManagerFactory;
use Zend\Session\Service\StorageFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Core42\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'factories' => [
            TransportInterface::class                       => TransportFactory::class,

            CommandPluginManager::class                     => CommandPluginManagerFactory::class,
            TableGatewayPluginManager::class                => TableGatewayPluginManagerFactory::class,
            HydratorStrategyPluginManager::class            => HydratorStrategyPluginManagerFactory::class,
            SelectorPluginManager::class                    => SelectorPluginManagerFactory::class,
            FormPluginManager::class                        => FormPluginManagerFactory::class,

            TreeRouteMatcher::class                         => TreeRouteMatcherFactory::class,

            Rbac::class                                     => RbacFactory::class,
            RbacManager::class                              => RbacManagerFactory::class,
            RoleProviderPluginManager::class                => RoleProviderPluginManagerFactory::class,
            GuardPluginManager::class                       => GuardPluginManagerFactory::class,
            AssertionPluginManager::class                   => AssertionPluginManagerFactory::class,
            RedirectStrategy::class                         => RedirectStrategyFactory::class,
            UnauthorizedStrategy::class                     => UnauthorizedStrategyFactory::class,

            NavigationOptions::class                        => NavigationOptionsFactory::class,
            Navigation::class                               => NavigationFactory::class,

            Localization::class                             => LocalizationFactory::class,

            LoggingProfiler::class                          => InvokableFactory::class,
            ConsoleDispatcher::class                        => ConsoleDispatcherFactory::class,
            TransactionManager::class                       => TransactionManagerFactory::class,


            'MvcTranslator'                                 => 'Core42\I18n\Translator\Service\TranslatorFactory',
            'MvcTranslatorPluginManager'                    => TranslatorLoaderFactory::class,

            'Zend\Session\Service\SessionManager'           => SessionManagerFactory::class,
            'Zend\Session\Config\ConfigInterface'           => SessionConfigFactory::class,
            'Zend\Session\Storage\StorageInterface'         => StorageFactory::class,
        ],
        'aliases' => [
            'Localization'                                  => Localization::class,
            'Permission'                                    => RbacManager::class,

            'Command'                                       => CommandPluginManager::class,
            'TableGateway'                                  => TableGatewayPluginManager::class,
            'HydratorStrategy'                              => HydratorStrategyPluginManager::class,
            'Selector'                                      => SelectorPluginManager::class,
            'Form'                                          => FormPluginManager::class,
            'Navigation'                                    => Navigation::class,

            'Core42\Mail\Transport'                         => TransportInterface::class,

            //Deprecated
            'Core42\Navigation'                             => Navigation::class,
            'Core42\Permission'                             => RbacManager::class,
            'TreeRouteMatcher'                              => TreeRouteMatcher::class,
            'Core42\FormPluginManager'                      => FormPluginManager::class,
            'Core42\SelectorPluginManager'                  => SelectorPluginManager::class,
            'Core42\HydratorStrategyPluginManager'          => HydratorStrategyPluginManager::class,
            'Core42\TableGatewayPluginManager'              => TableGatewayPluginManager::class,
            'Core42\CommandPluginManager'                   => CommandPluginManager::class,
        ],
    ],

    'table_gateway' => [
        'factories' => [
            'Core42\Migration' => MigrationTableGatewayFactory::class,
        ],
    ],
];
