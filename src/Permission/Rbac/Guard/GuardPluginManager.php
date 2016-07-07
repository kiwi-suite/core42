<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Guard;

use Zend\ServiceManager\AbstractPluginManager;

class GuardPluginManager extends AbstractPluginManager
{
    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * {@inheritDoc}
     */
    public function validate($instance)
    {
        if ($instance instanceof GuardInterface) {
            return;
        }

        throw new \Exception(sprintf(
            'Guard must implement "Core42\Permission\Rbac\Guard\GuardInterface", but "%s" was given',
            is_object($instance) ? get_class($instance) : gettype($instance)
        ));
    }
}
