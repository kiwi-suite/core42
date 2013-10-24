<?php
namespace Core42\Hydrator\Strategy\Database;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class PluginManager implements ServiceManagerAwareInterface
{
    private $serviceManager = null;

    private $plugins = array();

    private $defaultStrategy;

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

    public function initialize()
    {
        $config = $this->serviceManager->get("Config");
        if (!array_key_exists("database_hydrator_plugins", $config)) {
            return;
        }
        foreach ($config["database_hydrator_plugins"] as $className) {
            $object = new $className();
            if (!($object instanceof DatabaseStrategyInterface)) {
                continue;
            }
            $this->plugins[get_class($object)] = $object;
        }

        $this->defaultStrategy = new DefaultStrategy();
    }

    public function getStrategy(\Zend\Db\Metadata\Object\ColumnObject $column)
    {
        $return = null;

        $this->initialize();
        foreach ($this->plugins as $plugin){
            $return = $plugin->getStrategy($column);
            if ($return !== null) {
                break;
            }
        }

        if ($return === null) {
            $return = $this->defaultStrategy;
        }
        return $return;
    }
}
