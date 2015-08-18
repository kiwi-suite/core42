<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Model;

class Cron extends AbstractModel
{

    const STATUS_AUTO = 'auto';
    const STATUS_MANUAL = 'manual';
    const STATUS_DISABLED = 'disabled';

    protected $properties = [
        'id',
        'status',
        'name',
        'command',
        'parameters',
        'lock',
        'last_run',
        'next_run',
        'interval_minute',
        'interval_hour',
        'interval_day',
        'interval_month',
        'interval_day_of_week',
        'interval_year',
        'logfile',
        'created',
        'updated'
    ];

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->set('id', $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->set('status', $status);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('status');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->set('name', $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @param string $command
     * @return $this
     */
    public function setCommand($command)
    {
        $this->set('command', $command);
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->get('command');
    }

    /**
     * @param string $parameters
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->set('parameters', $parameters);
        return $this;
    }

    /**
     * @return string
     */
    public function getParameters()
    {
        return $this->get('parameters');
    }

    /**
     * @param \DateTime $lock
     * @return $this
     */
    public function setLock($lock)
    {
        $this->set('lock', $lock);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLock()
    {
        return $this->get('lock');
    }

    /**
     * @param \DateTime $last_run
     * @return $this
     */
    public function setLastRun($last_run)
    {
        $this->set('last_run', $last_run);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastRun()
    {
        return $this->get('last_run');
    }

    /**
     * @param \DateTime $next_run
     * @return $this
     */
    public function setNextRun($next_run)
    {
        $this->set('next_run', $next_run);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNextRun()
    {
        return $this->get('next_run');
    }

    /**
     * @return mixed
     */
    public function getIntervalMinute()
    {
        return $this->get('interval_minute');
    }

    /**
     * @param string $interval_minute
     * @return $this
     */
    public function setIntervalMinute($interval_minute)
    {
        $this->set('interval_minute', $interval_minute);
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalHour()
    {
        return $this->get('interval_hour');
    }

    /**
     * @param string $interval_hour
     * @return $this
     */
    public function setIntervalHour($interval_hour)
    {
        $this->set('interval_hour', $interval_hour);
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalDay()
    {
        return $this->get('interval_day');
    }

    /**
     * @param string $interval_day
     * @return $this
     */
    public function setIntervalDay($interval_day)
    {
        $this->set('interval_day', $interval_day);
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalMonth()
    {
        return $this->get('interval_month');
    }

    /**
     * @param string $interval_month
     * @return $this
     */
    public function setIntervalMonth($interval_month)
    {
        $this->set('interval_month', $interval_month);
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalDayOfWeek()
    {
        return $this->get('interval_day_of_week');
    }

    /**
     * @param $interval_day_of_week
     * @return $this
     */
    public function setIntervalDayOfWeek($interval_day_of_week)
    {
        $this->set('interval_day_of_week', $interval_day_of_week);
        return $this;
    }

    /**
     * @return string
     */
    public function getIntervalYear()
    {
        return $this->get('interval_year');
    }

    /**
     * @param $interval_year
     * @return $this
     */
    public function setIntervalYear($interval_year)
    {
        $this->set('interval_year', $interval_year);
        return $this;
    }

    /**
     * @return string
     */
    public function getCronInterval()
    {
        return $this->getIntervalMinute() . ' ' . $this->getIntervalHour() . ' ' . $this->getIntervalDay() . ' ' .
               $this->getIntervalMonth() . ' ' . $this->getIntervalDayOfWeek() . ' ' . $this->getIntervalYear();
    }

    /**
     * @param $string $logfile
     * @return $this
     */
    public function setLogfile($logfile)
    {
        $this->set('logfile', $logfile);
        return $this;
    }

    /**
     * @return $string
     */
    public function getLogfile()
    {
        return $this->get('logfile');
    }

    /**
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->set('created', $created);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->get('created');
    }

    /**
     * @param \DateTime $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->set('updated', $updated);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->get('updated');
    }
}
