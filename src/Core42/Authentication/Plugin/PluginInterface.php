<?php
namespace Core42\Authentication\Plugin;

use Zend\ServiceManager\ServiceManager;

interface PluginInterface
{
    public function setOptions(array $options, ServiceManager $serviceManager);
}
