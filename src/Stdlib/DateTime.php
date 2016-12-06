<?php
namespace Core42\Stdlib;

class DateTime extends \DateTime implements \JsonSerializable
{

    /**
     * @param string $format
     * @param string $time
     * @param \DateTimeZone|null $timezone
     * @return DateTime|bool
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

        return new DateTime($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->format('Y-m-d H:i:s');
    }
}
