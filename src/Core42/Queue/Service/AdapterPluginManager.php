<?php
namespace Core42\Queue\Service;

use Core42\Queue\Adapter\AdapterInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class AdapterPluginManager extends AbstractPluginManager
{
    /**
     * @var array
     */
    protected $invokableClasses = array(
        'table' => 'Core42\Queue\Adapter\TableAdapter',
    );

    /**
     * @var bool
     */
    protected $shareByDefault = false;

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
        if ($plugin instanceof AdapterInterface) {
            return;
        }

        throw new \Exception(sprintf(
            'Plugin of type %s is invalid; must implement %s\AdapterInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
