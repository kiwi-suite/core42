<?php
namespace Core42\Db\SelectQuery\Service;

use Core42\Db\SelectQuery\AbstractSelectQuery;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

class SelectQueryPluginManager extends AbstractPluginManager
{

    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        parent::__construct($configuration);

        $this->addAbstractFactory(new SelectQueryFallbackAbstractFactory(), false);
    }

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @throws \RuntimeException
     * @return void
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AbstractSelectQuery) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement \\Core42\\Db\\SelectQuery\\AbstractSelectQuery",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
