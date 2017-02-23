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


namespace Core42\Log\Service;

use Monolog\Logger;
use Zend\ServiceManager\AbstractPluginManager;

class LoggerPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = Logger::class;
}
