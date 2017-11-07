<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
