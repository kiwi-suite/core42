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

namespace Core42\Stdlib;

class Date extends \DateTime implements \JsonSerializable
{

    /**
     * @param string $format
     * @param string $time
     * @param \DateTimeZone|null $timezone
     * @return Date|bool
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        if ($timezone !== null) {
            $dateTime = parent::createFromFormat($format, $time, $timezone);
        } else {
            $dateTime = parent::createFromFormat($format, $time);
        }

        if (!$dateTime instanceof \DateTime) {
            return $dateTime;
        }

        return new Date($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
    }

    /**
     * Specify data which should be serialized to JSON
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->format('Y-m-d');
    }
}
