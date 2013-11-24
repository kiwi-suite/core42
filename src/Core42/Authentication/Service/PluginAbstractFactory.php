<?php
namespace Core42\Authentication\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return (isset($config[$requestedName]) && is_array($config[$requestedName]) && isset($config[$requestedName]['name']));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        $config = $config[$requestedName];

        $className = $config['name'];
        /** @var $obj \Core42\Authentication\Plugin\PluginInterface */
        $obj = new $className();
        if (isset($config['options']) && is_array($config['options'])) {
            $obj->setOptions($config['options'], $serviceLocator);
        }

        return $obj;
    }

    /**
     * Retrieve cache configuration, if any
     *
     * @param  ServiceLocatorInterface $services
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $services)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$services->has('Config')) {
            $this->config = array();

            return $this->config;
        }

        $config = $services->get('Config');
        if (!isset($config['authentication']['plugins'])) {
            $this->config = array();

            return $this->config;
        }

        $this->config = $config['authentication']['plugins'];

        return $this->config;
    }
}
