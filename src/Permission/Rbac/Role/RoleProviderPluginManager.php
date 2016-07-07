<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Role;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class RoleProviderPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = RoleProviderInterface::class;
}
