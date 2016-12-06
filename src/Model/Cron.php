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

namespace Core42\Model;
use Core42\Stdlib\DateTime;

/**
 * @method Cron setId() setId(int $id)
 * @method int getId() getId()
 * @method Cron setStatus() setStatus(string $status)
 * @method string getStatus() getStatus()
 * @method Cron setName() setName(string $name)
 * @method string getName() getName()
 * @method Cron setGroup() setGroup(string $group)
 * @method string getGroup() getGroup()
 * @method Cron setCommand() setCommand(string $command)
 * @method string getCommand() getCommand()
 * @method Cron setParameters() setParameters(string $parameters)
 * @method string getParameters() getParameters()
 * @method Cron setLock() setLock(DateTime $lock)
 * @method DateTime getLock() getLock()
 * @method Cron setLastRun() setLastRun(DateTime $last_run)
 * @method DateTime getLastRun() getLastRun()
 * @method Cron setNextRun() setNextRun(DateTime $next_run)
 * @method DateTime getNextRun() getNextRun()
 * @method Cron setIntervalMinute() setIntervalMinute(string $interval_minute)
 * @method string getIntervalMinute() getIntervalMinute()
 * @method Cron setIntervalHour() setIntervalHour(string $interval_hour)
 * @method string getIntervalHour() getIntervalHour()
 * @method Cron setIntervalDay() setIntervalDay(string $interval_day)
 * @method string getIntervalDay() getIntervalDay()
 * @method Cron setIntervalMonth() setIntervalMonth(string $interval_month)
 * @method string getIntervalMonth() getIntervalMonth()
 * @method Cron setIntervalDayOfWeek() setIntervalDayOfWeek(string $interval_day_of_week)
 * @method string getIntervalDayOfWeek() getIntervalDayOfWeek()
 * @method Cron setIntervalYear() setIntervalYear(string $interval_year)
 * @method string getIntervalYear() getIntervalYear()
 * @method Cron setCreated() setCreated(DateTime $created)
 * @method DateTime getCreated() getCreated()
 * @method Cron setUpdated() setUpdated(DateTime $updated)
 * @method DateTime getUpdated() getUpdated()
 */
class Cron extends AbstractModel
{
    const STATUS_AUTO = 'auto';
    const STATUS_MANUAL = 'manual';
    const STATUS_DISABLED = 'disabled';

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'status',
        'name',
        'group',
        'command',
        'parameters',
        'lock',
        'lastRun',
        'nextRun',
        'intervalMinute',
        'intervalHour',
        'intervalDay',
        'intervalMonth',
        'intervalDayOfWeek',
        'intervalYear',
        'created',
        'updated',
    ];

    public function getCronInterval()
    {
        return $this->getIntervalMinute() . ' ' . $this->getIntervalHour() . ' ' . $this->getIntervalDay() . ' ' .
        $this->getIntervalMonth() . ' ' . $this->getIntervalDayOfWeek() . ' ' . $this->getIntervalYear();
    }
}
