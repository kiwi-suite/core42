<?php
namespace Core42\Hydrator\Strategy\Service;

use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\ServiceManager\AbstractPluginManager;

class JsonHydratorPluginManager extends AbstractPluginManager
{
    /**
     * An object type that the created instance must be instanced of
     *
     * @var null|string
     */
    protected $instanceOf = StrategyInterface::class;
}
