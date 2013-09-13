<?php
namespace Core42\Db\DataConverter\Adapter;

interface AdapterInterface
{
    /**
     *
     * @param \DateTime $datetime
     * @return string
     */
    public function convertDatetimeToDb(\DateTime $datetime);

    /**
     *
     * @param string $value
     * @return \DateTime
     */
    public function convertDatetimeToLocal($value);

    /**
     *
     * @param boolean $boolean
     * @return string
     */
    public function convertBooleanToDb($boolean);

    /**
     *
     * @param string $value
     * @return boolean
     */
    public function convertBooleanToLocal($value);
}
