<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
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
    protected $table = 'core42_migration';

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
}
