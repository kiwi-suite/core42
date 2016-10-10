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
    protected $table = 'cron';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'Integer',
        'status' => 'String',
        'name' => 'String',
        'group' => 'String',
        'command' => 'String',
        'parameters' => 'String',
        'lock' => 'DateTime',
        'lastRun' => 'DateTime',
        'nextRun' => 'DateTime',
        'intervalMinute' => 'String',
        'intervalHour' => 'String',
        'intervalDay' => 'String',
        'intervalMonth' => 'String',
        'intervalDayOfWeek' => 'String',
        'intervalYear' => 'String',
        'created' => 'DateTime',
        'updated' => 'DateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Core42\\Model\\Cron';
}
