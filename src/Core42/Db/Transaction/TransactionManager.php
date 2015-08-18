<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Transaction;

use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class TransactionManager implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @var array
     */
    private $transactions = [];

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param string $name
     * @return AdapterInterface
     * @throws \Exception
     */
    protected function getAdapter($name)
    {
        if (!$this->serviceManager->has($name)) {
            throw new \Exception("database adapter named '{$name}' doesnt't exists");
        }

        $adapter = $this->serviceManager->get($name);
        if (!($adapter instanceof AdapterInterface)) {
            throw new \Exception("given service '{$name}' doesn't implement 'Zend\\Db\\Adapter\\AdapterInterface'");
        }

        return $adapter;
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function begin($name)
    {
        $adapter = $this->getAdapter($name);

        if (!array_key_exists($name, $this->transactions)) {
            $this->transactions[$name] = 0;
        }

        $this->transactions[$name]++;

        if ($this->transactions[$name] == 1) {
            $adapter->getDriver()->getConnection()->beginTransaction();
        }
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function commit($name, $force = false)
    {
        $adapter = $this->getAdapter($name);

        if (!array_key_exists($name, $this->transactions) || !($this->transactions[$name] > 0)) {
            throw new \Exception("no transaction started for '{$name}'");
        }

        if ($force) {
            $this->transactions[$name] = 0;
        } else {
            $this->transactions[$name]--;
        }


        if ($this->transactions[$name] == 0) {
            $adapter->getDriver()->getConnection()->commit();
        }
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function forceCommit($name)
    {
        $this->commit($name, true);
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function rollback($name)
    {
        $adapter = $this->getAdapter($name);

        if (!array_key_exists($name, $this->transactions)) {
            throw new \Exception("no transaction started for '{$name}'");
        }

        $this->transactions[$name] = 0;

        $adapter->getDriver()->getConnection()->rollback();
    }

    /**
     * @param callable $callback
     * @param string $name
     * @throws \Exception
     */
    public function transaction($name, $callback)
    {
        $this->begin($name);
        try {
            call_user_func($callback);
            $this->commit($name);

        } catch (\Exception $e) {
            $this->rollback($name);

            throw $e;
        }
    }
}
