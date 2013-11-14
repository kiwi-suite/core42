<?php
namespace Core42\Db\Migration;

use Core42\Migration\Migration;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceManager;

class Container implements \Iterator, \Countable
{
    /**
     * @var \SplObjectStorage
     */
    private $objectStorage;

    /**
     *
     */
    public function __construct()
    {
        $this->objectStorage = new \SplObjectStorage();
    }

    /**
     * @param Migration $migration
     */
    public function addMigration(Migration $migration)
    {
        $this->objectStorage->attach($migration);
    }

    /**
     * @param Migration $migration
     */
    public function removeMigration(Migration $migration)
    {
        $this->objectStorage->detach($migration);
    }

    /**
     * @param ServiceManager $serviceManager
     * @param $type
     * @throws \Exception
     */
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


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->objectStorage->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return $this->objectStorage->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->objectStorage->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->objectStorage->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->objectStorage->rewind();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->objectStorage->count();
    }
}
