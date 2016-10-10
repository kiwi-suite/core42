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

namespace Core42\Navigation\Service;

use Core42\Navigation\Filter\AbstractFilter;
use Zend\ServiceManager\AbstractPluginManager;

class FilterPluginManager extends AbstractPluginManager
{
    protected $instanceOf = AbstractFilter::class;
}
