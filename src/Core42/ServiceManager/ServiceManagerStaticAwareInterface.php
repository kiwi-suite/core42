<?php
namespace Core42\ServiceManager;

use Zend\ServiceManager\ServiceManager;

interface ServiceManagerStaticAwareInterface
{
    /**
     *
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager);
}
