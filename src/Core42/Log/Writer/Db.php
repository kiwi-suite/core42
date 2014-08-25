<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Log\Writer;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Log\Exception;
use Zend\Log\Formatter\Db as DbFormatter;
use Zend\Log\Writer\AbstractWriter;

class Db extends AbstractWriter
{
    /**
     * @var AbstractTableGateway
     */
    protected $tableGateway;

    /**
     * Relates database columns names to log data field keys.
     *
     * @var null|array
     */
    protected $columnMap;

    /**
     * Constructor
     *
     * @param AbstractTableGateway $tableGateway
     * @param array $columnMap
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($tableGateway, $columnMap = array())
    {
        $this->tableGateway = $tableGateway;

        $this->columnMap = $columnMap;

        if (!$this->hasFormatter()) {
            $this->setFormatter(new DbFormatter());
        }
    }

    /**
     * Remove reference to database adapter
     *
     * @return void
     */
    public function shutdown()
    {
        $this->tableGateway = null;
    }

    /**
     * Write a message to the log.
     *
     * @param array $event event data
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function doWrite(array $event)
    {
        if (null === $this->tableGateway) {
            throw new Exception\RuntimeException('TableGateway is null');
        }

        // Transform the event array into fields
        if (empty($this->columnMap)) {
            $dataToInsert = $this->eventIntoColumn($event);
        } else {
            $dataToInsert = $this->mapEventIntoColumn($event, $this->columnMap);
        }

        $this->tableGateway->insert($dataToInsert);
    }

    /**
     * Map event into column using the $columnMap array
     *
     * @param  array $event
     * @param  array $columnMap
     * @return array
     */
    protected function mapEventIntoColumn(array $event, array $columnMap = null)
    {
        if (empty($event)) {
            return array();
        }

        $data = array();
        foreach ($event as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $subvalue) {
                    if (isset($columnMap[$key])) {
                        $data[$columnMap[$key]] = $subvalue;
                    }
                }
            } elseif (isset($columnMap[$name])) {
                $data[$columnMap[$name]] = $value;
            }
        }
        return $data;
    }

    /**
     * Transform event into column for the db table
     *
     * @param  array $event
     * @return array
     */
    protected function eventIntoColumn(array $event)
    {
        if (empty($event)) {
            return array();
        }

        $data = array();
        foreach ($event as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $subvalue) {
                    $data[$key] = $subvalue;
                }
            } else {
                $data[$name] = $value;
            }
        }
        return $data;
    }
}
