<?php
namespace Core42\Db\TableGateway\Service;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

class TableGatewayPluginManager extends AbstractPluginManager
{

    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        parent::__construct($configuration);

        $this->addAbstractFactory(new TableGatewayFallbackAbstractFactory(), false);
    }

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
        if ($plugin instanceof AbstractTableGateway) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement \\Core42\\Db\\TableGateway\\AbstractTableGateway",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
