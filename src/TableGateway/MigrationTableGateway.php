<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Hydrator\BaseHydrator;
use Core42\Model\Migration;
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
        'name' => 'string',
        'created' => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = Migration::class;

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
