<?php
namespace Core42\Db\Migration;

use Core42\Migration\Migration;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceManager;

class Container
{
    /**
     * @var \SplObjectStorage
     */
    private $objectStorage;

    public function __construct()
    {
        $this->objectStorage = new \SplObjectStorage();
    }

    public function addMigration(Migration $migration)
    {
        $this->objectStorage->attach($migration);
    }

    public function removeMigration(Migration $migration)
    {
        $this->objectStorage->detach($migration);
    }

    public function execute(ServiceManager $serviceManager, $type)
    {
        /** @var $cache \Zend\Cache\Storage\Adapter\Filesystem */
        $cache = $serviceManager->get('Cache\InternStatic');
        $finishedMigrations = $cache->getItem("finishedMigrations");
        if (empty($finishedMigrations) || !is_array($finishedMigrations)) $finishedMigrations = array();

        /** @var $migration Migration */
        foreach ($this->objectStorage as $migration) {
            $sqlArray = $migration->getSql();
            foreach ($sqlArray as $_sql) {
                list($adapterName, $sql) = $_sql;
                if (!$serviceManager->has($adapterName)) {
                    throw new \Exception("invalid adapterName {$adapterName}");
                }

                /** @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $serviceManager->get($adapterName);

                $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            }

            if ($type == 'up') {
                $finishedMigrations[$migration->getVersion()] = $migration->getVersion();
            } else {
                unset($finishedMigrations[$migration->getVersion()]);
            }

        }
        $cache->setItem('finishedMigrations', $finishedMigrations);
    }


}
