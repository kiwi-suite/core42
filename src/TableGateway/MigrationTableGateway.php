<?php
namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\AbstractPluginManager;

class MigrationTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'migrations';

    /**
     * @var array
     */
    protected $primaryKey = ['name'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'name' => 'String',
        'created' => 'DateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Core42\\Model\\Migration';

    /**
     * MigrationTableGateway constructor.
     * @param Adapter $adapter
     * @param AbstractPluginManager $hydratorStrategyPluginManager
     * @param Adapter $tablename
     * @param null $slave
     */
    public function __construct(
        Adapter $adapter,
        AbstractPluginManager $hydratorStrategyPluginManager,
        $tablename,
        $slave = null
    ) {
        $this->table = $tablename;
        parent::__construct($adapter, $hydratorStrategyPluginManager, $slave);
    }
}
