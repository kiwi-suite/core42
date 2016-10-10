<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
     * @throws \Exception
     */
    public function forceCommit()
    {
        $this->commit(true);
    }

    /**
     * @throws \Exception
     */
    public function rollback()
    {
        if ($this->transactions == 0) {
            return;
        }
        $this->transactions = 0;

        foreach ($this->adapters as $adapter) {
            $adapter->getDriver()->getConnection()->rollback();
        }
    }

    /**
     * @param callable $callback
     * @return mixed|null
     * @throws \Exception
     */
    public function transaction($callback)
    {
        $return = null;

        $this->begin();
        try {
            $return = call_user_func($callback);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();

            throw $e;
        }

        return $return;
    }
}
