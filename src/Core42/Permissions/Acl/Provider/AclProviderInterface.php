<?php
namespace Core42\Permissions\Acl\Provider;

use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceManager;

interface AclProviderInterface
{
    /**
     * @param Acl $acl
     * @return null
     */
    public function provideAcl(Acl $acl, ServiceManager $serviceManager);
}
