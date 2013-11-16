<?php
namespace Core42\Db\Migration;

class Migration
{
    private $sql = array();

    private $defaultAdapterName;

    private $version;

    public function __construct($defaultAdapterName, $version)
    {
        $this->defaultAdapterName = $defaultAdapterName;
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function addSql($sql, $adapterName = null)
    {
        if ($adapterName === null) {
            $adapterName = $this->defaultAdapterName;
        }

        $this->sql[] = array(
            $adapterName,
            $sql
        );
    }
}
