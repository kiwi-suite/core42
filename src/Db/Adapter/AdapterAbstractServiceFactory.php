<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Adapter;

use Interop\Container\ContainerInterface;

class AdapterAbstractServiceFactory extends \Zend\Db\Adapter\AdapterAbstractServiceFactory
{

    /**
     * Get db configuration, if any
     *
     * @param  ContainerInterface $container
     * @return array
     */
    protected function getConfig(ContainerInterface $container)
    {
        if ($this->config !== null) {
            return $this->config;
        }
        $config = parent::getConfig($container);

        foreach ($config as &$_config) {
            if (array_key_exists('profiler', $_config)
                && is_array($_config['profiler'])
                && count($_config['profiler']) == 2) {
                $profilerConfig = $_config['profiler'];
                if ($container->has($profilerConfig[0])) {
                    $_config['profiler'] = $container->get($profilerConfig[0]);
                    $_config['profiler']->setLogger($container->get($profilerConfig[1]));
                }
            }
        }
        $this->config = $config;

        return $this->config;
    }
}
