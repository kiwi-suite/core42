<?php
namespace Core42\ModuleManager;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

trait GetConfigTrait
{
    public function getConfig()
    {
        $config = [];
        $configPath = dirname((new \ReflectionClass($this))->getFileName()) . '/../config/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
