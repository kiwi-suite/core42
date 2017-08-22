<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */


namespace Core42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Model\Migration;

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
