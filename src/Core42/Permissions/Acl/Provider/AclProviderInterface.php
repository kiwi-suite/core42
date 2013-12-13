<?php
namespace Core42\Permissions\Acl\Provider;

use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceManager;

interface AclProviderInterface
{
    /**
     * @param  Acl  $acl
     * @param ServiceManager $serviceManager
     * @return null
     */
    public function provideAcl(Acl $acl, ServiceManager $serviceManager);
}
