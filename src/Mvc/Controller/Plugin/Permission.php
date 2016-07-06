<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Mvc\Controller\Plugin;

use Core42\Permission\Rbac\RbacManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Permission extends AbstractPlugin
{
    /**
     * @var RbacManager
     */
    private $rbacManager;

    /**
     * @var string
     */
    private $serviceName;

    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(RbacManager $rbacManager)
    {
        $this->rbacManager = $rbacManager;
    }

    /**
     * @param string $serviceName
     * @return $this
     */
    public function __invoke($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * @param string $permission
     * @param null $context
     * @return bool
     */
    public function isGranted($permission, $context = null)
    {
        return $this->rbacManager->getService($this->serviceName)->isGranted($permission, $context);
    }
}
