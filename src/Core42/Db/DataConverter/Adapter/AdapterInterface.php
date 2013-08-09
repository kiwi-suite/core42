<?php
namespace Core42\Db\DataConverter\Adapter;

interface AdapterInterface
{

    public function convertDatetimeToDb(\DateTime $datetime);

    public function convertDatetimeToLocal($value);

    public function convertBooleanToDb($boolean);

    public function convertBooleanToLocal($value);
}
