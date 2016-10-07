<?php
namespace Core42\Permission\Service;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\ServiceManager\AbstractPluginManager;

class AssertionPluginManager extends AbstractPluginManager
{
    protected $instanceOf = AssertionInterface::class;
}
