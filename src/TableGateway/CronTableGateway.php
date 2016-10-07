<?php
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
