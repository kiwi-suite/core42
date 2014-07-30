<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Transaction;

use Zend\Db\Adapter\Adapter;

class TransactionManager
{
    /**
     * @var Adapter[]
     */
    private $adapters = array();

    /**
     * @var array
     */
    private $transactions = array();

    /**
     * @param $serviceName
     * @param Adapter $adapter
     */
    public function addAdapter($serviceName, Adapter $adapter)
    {
        $this->adapters[$serviceName] = $adapter;
    }

    /**
     * @param string|null $name
     * @return string|null
     * @throws \Exception
     */
    protected function getAdapterName($name = null)
    {
        if (count($this->adapters) === 0) {
            throw new \Exception('no database adapter is registered for using transactions');
        } elseif (count($this->adapters) === 1 && $name === null) {
            $name = current(array_keys($this->adapters));
        }

        if (!array_key_exists($name, $this->adapters)) {
            throw new \Exception(sprintf("there is no database adapter '%s' registered for using transactions", $name));
        }

        return $name;
    }

    /**
     * @param string|null $name
     * @throws \Exception
     */
    public function begin($name = null)
    {
        $name = $this->getAdapterName($name);

        if (!array_key_exists($name, $this->transactions)) {
            $this->transactions[$name] = 0;
        }

        $this->transactions[$name]++;

        $this->adapters[$name]->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * @param string|null $name
     * @throws \Exception
     */
    public function commit($name = null)
    {
        $name = $this->getAdapterName($name);

        if (!array_key_exists($name, $this->transactions) || !($this->transactions[$name] > 0)) {
            throw new \Exception("no transaction started for '{$name}'");
        }

        $this->transactions[$name]--;

        if ($this->transactions[$name] == 0) {
            $this->adapters[$name]->getDriver()->getConnection()->commit();
        }
    }

    /**
     * @param string|null $name
     * @throws \Exception
     */
    public function rollback($name = null)
    {
        $name = $this->getAdapterName($name);

        if (!array_key_exists($name, $this->transactions)) {
            throw new \Exception("no transaction started for '{$name}'");
        }

        $this->transactions[$name] = 0;

        $this->adapters[$name]->getDriver()->getConnection()->rollback();
    }

    /**
     * @param callable $callback
     * @param string|null $name
     * @throws \Exception
     */
    public function transaction($callback, $name = null)
    {
        $name = $this->getAdapterName($name);

        try {
            $this->begin($name);

            call_user_func($callback);

            $this->commit($name);

        } catch (\Exception $e) {
            $this->rollback($name);

            throw $e;
        }
    }
}
