<?php
use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;
use Core42\Error\ErrorHandler;

require 'config/settings.config.php';

if (file_exists('data/maintenance/on')) {
    http_response_code(503);

    ob_start();
    include_once 'data/maintenance/template.phtml';
    echo ob_get_clean();
    return;
}

if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

require 'vendor/autoload.php';
require 'config/environment.config.php';

try {
    ErrorHandler::setErrorTemplate('data/error/template.phtml');

    if (! class_exists(Application::class)) {
        throw new RuntimeException(
            "Unable to load application.\n"
            . "- Type `composer install` if you are developing locally.\n"
            . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
            . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
        );
    }
    ErrorHandler::registerShutdown();

    $appConfig = require 'config/application.config.php';
    $developmentConfig = require 'config/development.config.php';

    if (!empty($developmentConfig)) {
        $appConfig = ArrayUtils::merge($appConfig, $developmentConfig);
    }

    if ($appConfig['module_listener_options']['config_cache_enabled'] === true
        && !is_dir($appConfig['module_listener_options']['cache_dir'])
    ) {
        @mkdir($appConfig['module_listener_options']['cache_dir'], 0777, true);
    }

    Application::init($appConfig)->run();
} catch (\Throwable $e) {
    echo ErrorHandler::init($e);
} catch (\Exception $e) {
    echo ErrorHandler::init($e);
}
