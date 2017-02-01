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

namespace Core42\Test\PHPUnit;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Bootstrap
{
    /**
     * @var array
     */
    protected static $config = [
        'modules' => [],
        'module_listener_options' => [
            'module_paths'              => [
                './module',
                './vendor',
            ],
            'config_cache_enabled'      => false,
            'module_map_cache_enabled'  => false,
            'check_dependencies'        => false,
        ],
    ];

    /**
     * @var ServiceManager
     */
    protected static $serviceManager;

    /**
     * @param array $modules
     */
    public static function init(array $modules)
    {
        self::setupServiceManager($modules);
    }

    /**
     * @param array $modules
     */
    protected static function setupServiceManager(array $modules)
    {
        $config = self::$config;
        $config['modules'] = $modules;

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        self::$serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }
}