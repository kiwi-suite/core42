<?php
namespace Core42\Permission\Service;

use Core42\Permission\PermissionInterface;
use Zend\ServiceManager\AbstractPluginManager;

class PermissionPluginManager extends AbstractPluginManager
{
    protected $instanceOf = PermissionInterface::class;
}
