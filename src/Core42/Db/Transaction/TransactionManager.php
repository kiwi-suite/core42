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

class TransactionManager
{
    /**
     * @var array
     */
    private $transactions = 0;

    /**
     * @var AdapterInterface[]
     */
    protected $adapters = [];

    /**
     * @param array $adapters
     */
    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @throws \Exception
     */
    public function begin()
    {
        $this->transactions++;

        if ($this->transactions == 1) {
            foreach ($this->adapters as $adapter) {
                $adapter->getDriver()->getConnection()->beginTransaction();
            }
        }
    }

    /**
     * @param bool $force
     */
    public function commit($force = false)
    {
        if ($force) {
            $this->transactions = 0;
        } else {
            $this->transactions--;
        }


        if ($this->transactions == 0) {
            foreach ($this->adapters as $adapter) {
                $adapter->getDriver()->getConnection()->commit();
            }
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
     * @throws \Exception
     */
    public function rollback()
    {
        $this->transactions = 0;

        foreach ($this->adapters as $adapter) {
            $adapter->getDriver()->getConnection()->rollback();
        }
    }

    /**
     * @param callable $callback
     * @throws \Exception
     */
    public function transaction($callback)
    {
        $this->begin();
        try {
            call_user_func($callback);
            $this->commit();

        } catch (\Exception $e) {
            $this->rollback();

            throw $e;
        }
    }
}
