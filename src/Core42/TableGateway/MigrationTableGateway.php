<?php
namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Core42\Hydrator\Strategy\Database\DatabasePluginManagerInterface;

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

    public function __construct(Adapter $adapter, Adapter $slave, Metadata $metadata, DatabasePluginManagerInterface $hydratorStrategyPluginManager, $tablename)
    {
        $this->table = $tablename;
        parent::__construct($adapter, $slave, $metadata, $hydratorStrategyPluginManager);
    }
}
