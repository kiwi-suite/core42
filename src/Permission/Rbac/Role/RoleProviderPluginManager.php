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
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws \Exception if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof RoleProviderInterface) {
            return;
        }

        throw new \Exception(sprintf(
            'Role provider must implement "Core42\Permission\Rbac\Role\RoleProviderInterface", but "%s" was given',
            is_object($plugin) ? get_class($plugin) : gettype($plugin)
        ));
    }
}
