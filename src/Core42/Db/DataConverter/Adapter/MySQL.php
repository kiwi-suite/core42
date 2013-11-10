<?php
namespace Core42\Db\DataConverter\Adapter;

class MySQL implements AdapterInterface
{
    /**
     *
     * @see \Core42\Db\DataConverter\Adapter\AdapterInterface::convertDatetimeToDb()
     */
    public function convertDatetimeToDb(\DateTime $datetime)
    {
        return date("Y-m-d H:i:s", $datetime->getTimestamp());
    }

    /**
     *
     * @see \Core42\Db\DataConverter\Adapter\AdapterInterface::convertDatetimeToLocal()
     */
    public function convertDatetimeToLocal($value)
    {
        return new \DateTime($value);
    }

    /**
     *
     * @see \Core42\Db\DataConverter\Adapter\AdapterInterface::convertBooleanToDb()
     */
    public function convertBooleanToDb($boolean)
    {
        return ($boolean === true) ? "true" : "false";
    }

    /**
     *
     * @see \Core42\Db\DataConverter\Adapter\AdapterInterface::convertBooleanToLocal()
     */
    public function convertBooleanToLocal($value)
    {
        return ($value === "true") ? true : false;
    }
}
