<?php
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
        'module_listener_options' => array(
            'module_paths'              => array(
                './module',
                './vendor',
            ),
            'config_cache_enabled'      => false,
            'module_map_cache_enabled'  => false,
            'check_dependencies'        => false,
        ),
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
