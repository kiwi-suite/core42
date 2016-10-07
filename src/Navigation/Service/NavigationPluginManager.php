<?php
namespace Core42\Navigation\Service;

use Core42\Navigation\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

class NavigationPluginManager extends AbstractPluginManager
{
    protected $instanceOf = ContainerInterface::class;
}
