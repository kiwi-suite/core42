<?php
namespace Core42;

use Core42\Cache\Service\CachePluginManager;
use Core42\Cache\Service\CachePluginManagerFactory;
use Core42\Cache\Service\DriverPluginManager;
use Core42\Cache\Service\DriverPluginManagerFactory;
use Core42\Command\Console\ConsoleDispatcher;
use Core42\Command\Console\Service\ConsoleDispatcherFactory;
use Core42\Command\Service\CommandPluginManager;
use Core42\Command\Service\CommandPluginManagerFactory;
use Core42\Db\Adapter\Service\AdapterFactory;
use Core42\Db\TableGateway\Service\TableGatewayPluginManager;
use Core42\Db\TableGateway\Service\TableGatewayPluginManagerFactory;
use Core42\Db\Transaction\Service\TransactionManagerFactory;
use Core42\Db\Transaction\TransactionManager;
use Core42\Form\Service\FormPluginManager;
use Core42\Form\Service\FormPluginManagerFactory;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManager;
use Core42\Hydrator\Strategy\Service\HydratorStrategyPluginManagerFactory;
use Core42\Hydrator\Strategy\Service\JsonHydratorPluginManager;
use Core42\Hydrator\Strategy\Service\JsonHydratorPluginManagerFactory;
use Core42\I18n\Localization\Localization;
use Core42\I18n\Localization\Service\LocalizationFactory;
use Core42\I18n\Translator\Service\TranslatorFactory;
use Core42\Log\Service\HandlerPluginManager;
use Core42\Log\Service\HandlerPluginManagerFactory;
use Core42\Log\Service\LoggerFactory;
use Core42\Mail\Transport\Service\TransportFactory;
use Core42\Mvc\Environment\Environment;
use Core42\Mvc\TreeRouteMatcher\Service\TreeRouteMatcherFactory;
use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Core42\Navigation\Navigation;
use Core42\Navigation\Options\NavigationOptions;
use Core42\Navigation\Service\NavigationFactory;
use Core42\Navigation\Service\NavigationOptionsFactory;
use Core42\Permission\Service\AssertionPluginManager;
use Core42\Permission\Service\AssertionPluginManagerFactory;
use Core42\Permission\Service\PermissionPluginManager;
use Core42\Permission\Service\PermissionPluginManagerFactory;
use Core42\Selector\Service\SelectorPluginManager;
use Core42\Selector\Service\SelectorPluginManagerFactory;
use Core42\TableGateway\MigrationTableGateway;
use Core42\TableGateway\Service\MigrationTableGatewayFactory;
use Core42\View\Http\Service\ExceptionStrategyFactory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Service\SessionConfigFactory;
use Zend\Session\Service\SessionManagerFactory;
use Zend\Session\Service\StorageFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
        ],
        'factories' => [
            CommandPluginManager::class                     => CommandPluginManagerFactory::class,
            TableGatewayPluginManager::class                => TableGatewayPluginManagerFactory::class,
            HydratorStrategyPluginManager::class            => HydratorStrategyPluginManagerFactory::class,
            SelectorPluginManager::class                    => SelectorPluginManagerFactory::class,
            FormPluginManager::class                        => FormPluginManagerFactory::class,

            JsonHydratorPluginManager::class                => JsonHydratorPluginManagerFactory::class,

            TreeRouteMatcher::class                         => TreeRouteMatcherFactory::class,

            NavigationOptions::class                        => NavigationOptionsFactory::class,
            Navigation::class                               => NavigationFactory::class,

            PermissionPluginManager::class                  => PermissionPluginManagerFactory::class,
            AssertionPluginManager::class                   => AssertionPluginManagerFactory::class,

            Localization::class                             => LocalizationFactory::class,

            ConsoleDispatcher::class                        => ConsoleDispatcherFactory::class,
            TransactionManager::class                       => TransactionManagerFactory::class,

            Environment::class                              => InvokableFactory::class,

            HandlerPluginManager::class                     => HandlerPluginManagerFactory::class,
            'Log\Core'                                      => LoggerFactory::class,
            'Log\Test'                                      => LoggerFactory::class,

            TranslatorInterface::class                      => TranslatorFactory::class,

            'Zend\Session\Service\SessionManager'           => SessionManagerFactory::class,
            'Zend\Session\Config\ConfigInterface'           => SessionConfigFactory::class,
            'Zend\Session\Storage\StorageInterface'         => StorageFactory::class,

            'Db\Master'                                     => AdapterFactory::class,

            'HttpExceptionStrategy'                         => ExceptionStrategyFactory::class,

            'Core42\Mail\Transport'                         => TransportFactory::class,
            CachePluginManager::class                       => CachePluginManagerFactory::class,
            DriverPluginManager::class                      => DriverPluginManagerFactory::class,
        ],
        'aliases' => [
            AdapterInterface::class                         => 'Db\Master',

            'Command'                                       => CommandPluginManager::class,
            'TableGateway'                                  => TableGatewayPluginManager::class,
            'HydratorStrategy'                              => HydratorStrategyPluginManager::class,
            'Selector'                                      => SelectorPluginManager::class,
            'Form'                                          => FormPluginManager::class,
            'Navigation'                                    => Navigation::class,
            'Cache'                                         => CachePluginManager::class,

            //Deprecated
            'Localization'                                  => Localization::class,
            'Core42\Navigation'                             => Navigation::class,
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
            MigrationTableGateway::class => MigrationTableGatewayFactory::class,
        ],
    ],
];
