<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Selector\Service;

use Core42\Db\SelectQuery\AbstractSelectQuery;
use Core42\Selector\SelectorInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class SelectorPluginManager extends AbstractPluginManager
{

    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        $this->addAbstractFactory(new SelectorFallbackAbstractFactory(), false);

        parent::__construct($configuration);
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
        if ($plugin instanceof SelectorInterface) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement \\Core42\\Selector\\SelectorInterface",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
