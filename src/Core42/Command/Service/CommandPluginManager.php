<?php
namespace Core42\Command\Service;

use Core42\Command\CommandInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

class CommandPluginManager extends AbstractPluginManager
{

    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        parent::__construct($configuration);

        $this->addAbstractFactory(new CommandFallbackAbstractFactory(), false);
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
        if ($plugin instanceof CommandInterface) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement \\Core42\\Command\\CommandInterface",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
