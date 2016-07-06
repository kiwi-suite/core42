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
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

class GuardPluginManager extends AbstractPluginManager
{
    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);
        parent::__construct($configuration);
    }
    /**
     * @param  mixed $plugin
     * @return void
     * @throws \Exception if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof GuardInterface) {
            return;
        }

        throw new \Exception(sprintf(
            'Guard must implement "Core42\Permission\Rbac\Guard\GuardInterface", but "%s" was given',
            is_object($plugin) ? get_class($plugin) : gettype($plugin)
        ));
    }
}
