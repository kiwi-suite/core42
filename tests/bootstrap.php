<?php
/**
 * namespace definition and usage
 */
namespace Core42Test;

use RuntimeException;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

/**
 * PHP Configuration
 */
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Bootstrap
 *
 * Handles the bootstrapping for the unit tests of the Customer module
 *
 * @package    Customer
 */
class Bootstrap
{
    /**
     * @var ServiceManager
     */
    protected static $serviceManager;

    /**
     * Get the service manager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    /**
     * Initialize the Bootstrapping
     */
    public static function init()
    {
        static::initAutoloader();

        $config = array(
            'modules' => array(
                'Core42',
            ),

            'module_listener_options' => array(

                'module_paths' => array(
                    '../..',
                    './vendor',
                ),
            ),
        );

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
    }

    /**
     * Initialize autoloader
     */
    protected static function initAutoloader()
    {
        $loader = null;
        if (file_exists('../vendor/autoload.php')) {
            $loader = include '../vendor/autoload.php';
        } else if (file_exists('../../../vendor/autoload.php')) {
            $loader = include '../../../vendor/autoload.php';
        } else {
            throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
        }

        AutoloaderFactory::factory(
            array(
                'Zend\Loader\StandardAutoloader' => array(
                    'autoregister_zf' => true,
                    'namespaces'      => array(
                        __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                    ),
                ),
            )
        );
    }
}

/**
 * Start bootstrapping
 */
Bootstrap::init();
