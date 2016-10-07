<?php
namespace Core42\Cache\Service;

use Psr\Cache\CacheItemPoolInterface;
use Zend\ServiceManager\AbstractPluginManager;

class CachePluginManager extends AbstractPluginManager
{
    /**
     * @var
     */
    protected $instanceOf = CacheItemPoolInterface::class;
}
