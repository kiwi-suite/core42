<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Service;

use Core42\Command\CommandInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class CommandPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = CommandInterface::class;

    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * CommandPluginManager constructor.
     * @param \Interop\Container\ContainerInterface|null|ConfigInterface $configInstanceOrParentLocator
     * @param array $config
     */
    public function __construct($configInstanceOrParentLocator, array $config)
    {
        $this->addAbstractFactory(new CommandFallbackAbstractFactory());

        parent::__construct($configInstanceOrParentLocator, $config);
    }
}
