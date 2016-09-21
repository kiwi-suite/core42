<?php
namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\BaseHydrator;
use Zend\Db\Adapter\Adapter;

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
     * @param BaseHydrator $hydrator
     * @param Adapter $tablename
     * @param null $slave
     */
    public function __construct(
        Adapter $adapter,
        BaseHydrator $hydrator,
        $tablename,
        $slave = null
    ) {
        $this->table = $tablename;
        parent::__construct($adapter, $hydrator, $slave);
    }
}
