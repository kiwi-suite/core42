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

namespace Core42\Navigation\Service;

use Core42\Navigation\Filter\AbstractFilter;
use Zend\ServiceManager\AbstractPluginManager;

class FilterPluginManager extends AbstractPluginManager
{
    protected $instanceOf = AbstractFilter::class;
}
