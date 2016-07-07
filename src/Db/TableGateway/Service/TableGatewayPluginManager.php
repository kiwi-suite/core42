<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Service;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\ServiceManager\AbstractPluginManager;

class TableGatewayPluginManager extends AbstractPluginManager
{

    /**
     * @var string
     */
    protected $instanceOf = AbstractTableGateway::class;

    /**
     * TableGatewayPluginManager constructor.
     * @param \Interop\Container\ContainerInterface|null|\Zend\ServiceManager\ConfigInterface $configInstanceOrParentLocator
     * @param array $config
     */
    public function __construct($configInstanceOrParentLocator, array $config)
    {
        $this->addAbstractFactory(new TableGatewayFallbackAbstractFactory());

        parent::__construct($configInstanceOrParentLocator, $config);
    }
}
