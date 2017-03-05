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


namespace Core42\ModuleManager;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

trait GetConfigTrait
{
    public function getConfig()
    {
        $config = [];
        $configPath = \dirname((new \ReflectionClass($this))->getFileName()) . '/../config/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
