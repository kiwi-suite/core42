<?php
namespace Core42\ServiceManager;

use Zend\ServiceManager\ServiceManager;
interface ServiceManagerStaticAwareInterface
{
    public static function setServiceManager(ServiceManager $serviceManager);    
}
