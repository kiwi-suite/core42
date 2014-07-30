<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
