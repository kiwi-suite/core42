<?php
namespace Core42\Db\Adapter;

use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterAbstractServiceFactory extends \Zend\Db\Adapter\AdapterAbstractServiceFactory
{

    /**
     * Get db configuration, if any
     *
     * @param  ServiceLocatorInterface $services
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $services)
    {
        if ($this->config !== null) {
            return $this->config;
        }
        $config = parent::getConfig($services);

        foreach ($config as &$_config) {
            if (array_key_exists('profiler', $_config) && is_array($_config['profiler']) && count($_config['profiler']) == 2) {
                $profilerConfig = $_config['profiler'];
                if ($services->has($profilerConfig[0])) {
                    $_config['profiler'] = $services->get($profilerConfig[0]);
                    $_config['profiler']->setLogger($services->get($profilerConfig[1]));
                }
            }
        }
        $this->config = $config;

        return $this->config;
    }
}
