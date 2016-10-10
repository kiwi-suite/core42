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

namespace Core42\Permission\Service;

use Core42\Permission\PermissionInterface;
use Zend\ServiceManager\AbstractPluginManager;

class PermissionPluginManager extends AbstractPluginManager
{
    protected $instanceOf = PermissionInterface::class;
}
