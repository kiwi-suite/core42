<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Database\MySQL;

use Core42\Hydrator\Strategy\Database\DatabasePluginManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;
use Core42\Hydrator\Strategy\Database\DatabaseStrategyInterface;

class PluginManager implements ServiceManagerAwareInterface, DatabasePluginManagerInterface
{
    private $serviceManager = null;

    private $plugins = array();

    private $defaultStrategy;

    private $initialized = false;

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     *
     */
    public function initialize()
    {
        $config = $this->serviceManager->get("Config");
        if (!array_key_exists("database_hydrator_plugins", $config)) {
            return;
        }
        if (!array_key_exists("mysql", $config['database_hydrator_plugins'])) {
            return;
        }

        if ($this->initialized === true) {
            return;
        }

        foreach ($config["database_hydrator_plugins"]['mysql'] as $className) {
            $object = new $className();
            if (!($object instanceof DatabaseStrategyInterface)) {
                continue;
            }
            $this->plugins[get_class($object)] = $object;
        }

        $this->defaultStrategy = new DefaultStrategy();
        $this->initialized = true;
    }

    /**
     * @param \Zend\Db\Metadata\Object\ColumnObject $column
     * @return DatabaseStrategyInterface|null
     */
    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        $return = null;

        $this->initialize();
        foreach ($this->plugins as $plugin) {
            $return = $plugin->getStrategy($column);
            if ($return !== null) {
                break;
            }
        }

        if ($return === null) {
            $return = $this->defaultStrategy;
        }

        return clone $return;
    }

    /**
     * @param $name
     * @return DatabaseStrategyInterface|null
     */
    public function loadStrategy($name)
    {
        $return = null;

        $this->initialize();
        $config = $this->serviceManager->get("Config");
        $return = (isset($config["database_hydrator_plugins"]['mysql']) && array_key_exists($name, $config["database_hydrator_plugins"]['mysql']))
                    ? $this->plugins[$config["database_hydrator_plugins"]['mysql'][$name]]
                    : null;

        if ($return === null) {
            $return = $this->defaultStrategy;
        }

        return clone $return;
    }
}
