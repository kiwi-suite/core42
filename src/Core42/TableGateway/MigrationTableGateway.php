<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\ServiceManager\AbstractPluginManager;

class MigrationTableGateway extends AbstractTableGateway
{
    /**
     *
     * @var string
     */
    protected $table = '';

    /**
     *
     * @var string
     */
    protected $modelPrototype = 'Core42\Model\Migration';

    /**
     * @param Adapter $adapter
     * @param Adapter $slave
     * @param Metadata $metadata
     * @param AbstractPluginManager $hydratorStrategyPluginManager
     * @param $tablename
     * @throws \Exception
     */
    public function __construct(
        Adapter $adapter,
        Metadata $metadata,
        AbstractPluginManager $hydratorStrategyPluginManager,
        $tablename,
        $slave = null
    ) {
        $this->table = $tablename;
        parent::__construct($adapter, $metadata, $hydratorStrategyPluginManager, $slave);
    }
}
