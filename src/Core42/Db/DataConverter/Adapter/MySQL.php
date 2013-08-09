<?php
namespace Core42\Db\DataConverter\Adapter;


class MySQL implements AdapterInterface
{
    public function convertDatetimeToDb(\DateTime $datetime)
    {
        return date("Y-m-d H:i:s", $datetime->getTimestamp());
    }

    public function convertDatetimeToLocal($value)
    {
        return new \DateTime($value);
    }

    public function convertBooleanToDb($boolean)
    {
        return ($boolean === true) ? 1 : 0;
    }

    public function convertBooleanToLocal($value)
    {
        return (boolean) $value;
    }
}
