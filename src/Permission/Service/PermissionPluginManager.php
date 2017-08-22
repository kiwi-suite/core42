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


namespace Core42\Permission\Service;

use Core42\Permission\PermissionInterface;
use Zend\ServiceManager\AbstractPluginManager;

class PermissionPluginManager extends AbstractPluginManager
{
    protected $instanceOf = PermissionInterface::class;
}
