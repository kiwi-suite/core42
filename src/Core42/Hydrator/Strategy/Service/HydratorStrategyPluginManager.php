<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator\Strategy\Service;

use Core42\Hydrator\Strategy\Database\DatabaseStrategyInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class HydratorStrategyPluginManager extends AbstractPluginManager
{
    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws \RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof DatabaseStrategyInterface) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement "
            . "\\Core42\\Hydrator\\Strategy\\Database\\DatabaseStrategyInterface",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }

    /**
     * DO NOT USE IT! Abstract Factories are disabled here
     *
     * @param  AbstractFactoryInterface|string $factory
     * @param  bool                            $topOfStack
     * @return void
     * @throws Exception\RuntimeException thrown on every call
     */
    public function addAbstractFactory($factory, $topOfStack = true)
    {
        throw new Exception\RuntimeException(
            'Abstract factories are not allowed in hydrator strategy plugin manager'
        );
    }
}
