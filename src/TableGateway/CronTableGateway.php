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
        'last_run' => 'DateTime',
        'next_run' => 'DateTime',
        'interval_minute' => 'String',
        'interval_hour' => 'String',
        'interval_day' => 'String',
        'interval_month' => 'String',
        'interval_day_of_week' => 'String',
        'interval_year' => 'String',
        'logfile' => 'String',
        'created' => 'DateTime',
        'updated' => 'DateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Core42\\Model\\Cron';
}
