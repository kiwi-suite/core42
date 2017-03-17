<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42;

use Cocur\Slugify\Slugify;
use Core42\Asset\Hash\DefaultCommitHash;
use Core42\Asset\Hash\LongCommitHash;
use Core42\Asset\Hash\ShortCommitHash;
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
use Core42\Form\Service\FormElementManagerFactory;
use Core42\Form\Service\FormPluginManager;
use Core42\Form\Service\FormPluginManagerFactory;
use Core42\Hydrator\Mutator\Mutator;
use Core42\Hydrator\Mutator\Service\MutatorFactory;
use Core42\Hydrator\Strategy\Service\StrategyPluginManager;
use Core42\Hydrator\Strategy\Service\StrategyPluginManagerFactory;
use Core42\I18n\Localization\Localization;
use Core42\I18n\Localization\Service\LocalizationFactory;
use Core42\I18n\Translator\Service\TranslatorFactory;
use Core42\Log\Service\HandlerPluginManager;
use Core42\Log\Service\HandlerPluginManagerFactory;
use Core42\Log\Service\LoggerPluginManager;
use Core42\Log\Service\LoggerPluginManagerFactory;
use Core42\Mail\Transport\Service\TransportFactory;
use Core42\Mvc\TreeRouteMatcher\Service\TreeRouteMatcherFactory;
use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Core42\Navigation\Service\FilterPluginManager;
use Core42\Navigation\Service\FilterPluginManagerFactory;
use Core42\Navigation\Service\NavigationPluginManager;
use Core42\Navigation\Service\NavigationPluginManagerFactory;
use Core42\Permission\Service\AssertionPluginManager;
use Core42\Permission\Service\AssertionPluginManagerFactory;
use Core42\Permission\Service\PermissionPluginManager;
use Core42\Permission\Service\PermissionPluginManagerFactory;
use Core42\Security\Csp\Csp;
use Core42\Security\Csp\Service\CspFactory;
use Core42\Selector\Service\SelectorPluginManager;
use Core42\Selector\Service\SelectorPluginManagerFactory;
use Core42\TableGateway\MigrationTableGateway;
use Core42\TableGateway\Service\MigrationTableGatewayFactory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Service\SessionConfigFactory;
use Zend\Session\Service\SessionManagerFactory;
use Zend\Session\Service\StorageFactory;
use Core42\Hydrator\Mutator\Service\StrategyPluginManager as MutatorStrategyPluginManager;
use Core42\Hydrator\Mutator\Service\StrategyPluginManagerFactory as MutatorStrategyPluginManagerFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
        ],
        'factories' => [
            CommandPluginManager::class                     => CommandPluginManagerFactory::class,
            TableGatewayPluginManager::class                => TableGatewayPluginManagerFactory::class,
            SelectorPluginManager::class                    => SelectorPluginManagerFactory::class,

            FormPluginManager::class                        => FormPluginManagerFactory::class,
            'FormElementManager'                            => FormElementManagerFactory::class,

            TreeRouteMatcher::class                         => TreeRouteMatcherFactory::class,

            PermissionPluginManager::class                  => PermissionPluginManagerFactory::class,
            AssertionPluginManager::class                   => AssertionPluginManagerFactory::class,

            NavigationPluginManager::class                  => NavigationPluginManagerFactory::class,
            FilterPluginManager::class                      => FilterPluginManagerFactory::class,

            Localization::class                             => LocalizationFactory::class,

            Slugify::class                                  => InvokableFactory::class,

            ConsoleDispatcher::class                        => ConsoleDispatcherFactory::class,
            TransactionManager::class                       => TransactionManagerFactory::class,

            HandlerPluginManager::class                     => HandlerPluginManagerFactory::class,
            LoggerPluginManager::class                      => LoggerPluginManagerFactory::class,

            TranslatorInterface::class                      => TranslatorFactory::class,

            'Zend\Session\Service\SessionManager'           => SessionManagerFactory::class,
            'Zend\Session\Config\ConfigInterface'           => SessionConfigFactory::class,
            'Zend\Session\Storage\StorageInterface'         => StorageFactory::class,

            'Db\Master'                                     => AdapterFactory::class,

            'Core42\Mail\Transport'                         => TransportFactory::class,

            CachePluginManager::class                       => CachePluginManagerFactory::class,
            DriverPluginManager::class                      => DriverPluginManagerFactory::class,

            StrategyPluginManager::class                    => StrategyPluginManagerFactory::class,

            Mutator::class                                  => MutatorFactory::class,
            MutatorStrategyPluginManager::class             => MutatorStrategyPluginManagerFactory::class,

            DefaultCommitHash::class                        => InvokableFactory::class,
            ShortCommitHash::class                          => InvokableFactory::class,
            LongCommitHash::class                           => InvokableFactory::class,

            Csp::class                                      => CspFactory::class,
        ],
        'aliases' => [
            AdapterInterface::class                         => 'Db\Master',

            'Command'                                       => CommandPluginManager::class,
            'TableGateway'                                  => TableGatewayPluginManager::class,
            'Selector'                                      => SelectorPluginManager::class,
            'Form'                                          => FormPluginManager::class,
            'Cache'                                         => CachePluginManager::class,
            'Logger'                                        => LoggerPluginManager::class,
        ],
    ],

    'table_gateway' => [
        'factories' => [
            MigrationTableGateway::class => MigrationTableGatewayFactory::class,
        ],
    ],
];
