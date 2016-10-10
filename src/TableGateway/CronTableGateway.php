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

class CronTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = 'core42_cron';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'integer',
        'status' => 'string',
        'name' => 'string',
        'group' => 'string',
        'command' => 'string',
        'parameters' => 'string',
        'lock' => 'dateTime',
        'lastRun' => 'dateTime',
        'nextRun' => 'dateTime',
        'intervalMinute' => 'string',
        'intervalHour' => 'string',
        'intervalDay' => 'string',
        'intervalMonth' => 'string',
        'intervalDayOfWeek' => 'string',
        'intervalYear' => 'string',
        'created' => 'dateTime',
        'updated' => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Core42\\Model\\Cron';
}
