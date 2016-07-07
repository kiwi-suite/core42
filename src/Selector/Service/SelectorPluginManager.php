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
     * @var string
     */
    protected $instanceOf = SelectorInterface::class;

    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * SelectorPluginManager constructor.
     * @param \Interop\Container\ContainerInterface|null|ConfigInterface $configInstanceOrParentLocator
     * @param array $config
     */
    public function __construct($configInstanceOrParentLocator, array $config)
    {
        $this->addAbstractFactory(new SelectorFallbackAbstractFactory());

        parent::__construct($configInstanceOrParentLocator, $config);
    }
}
