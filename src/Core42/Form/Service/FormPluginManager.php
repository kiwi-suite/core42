<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Form\Service;

use Zend\Form\FormInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class FormPluginManager extends AbstractPluginManager
{
    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        parent::__construct($configuration);

        $this->addAbstractFactory(new FormFallbackAbstractFactory(), false);
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
        if ($plugin instanceof FormInterface) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement Zend\\Form\\FormInterface",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
