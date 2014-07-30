<?php
namespace Core42\Queue\Service;

use Core42\Queue\Queue;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QueueAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @var string
     */
    protected $configKey = 'queue';

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
        $config  = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return isset($config[$requestedName]);
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
        $config  = $this->getConfig($serviceLocator);
        $config = $config[$requestedName];
        $this->processConfig($config, $serviceLocator);

        return new Queue($serviceLocator->get('QueueAdapter'), $serviceLocator->get('Command'), $config);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$serviceLocator->has('Config')) {
            $this->config = array();
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])) {
            $this->config = array();
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }

    /**
     * @param $config
     * @param ServiceLocatorInterface $serviceLocator
     */
    protected function processConfig(&$config, ServiceLocatorInterface $serviceLocator)
    {
        if (!isset($config['name']) || $config['name'] != 'table') {
            return;
        }

        if (!isset($config['params']['db_adapter'])){
            return;
        }

        $config['params']['db_adapter'] = $serviceLocator->get($config['params']['db_adapter']);
    }
}
