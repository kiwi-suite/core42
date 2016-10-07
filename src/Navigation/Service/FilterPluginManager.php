<?php
namespace Core42\Navigation\Service;

use Core42\Navigation\Filter\AbstractFilter;
use Zend\ServiceManager\AbstractPluginManager;

class FilterPluginManager extends AbstractPluginManager
{
    protected $instanceOf = AbstractFilter::class;
}
