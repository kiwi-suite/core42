<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Selector;

trait CacheAbleTrait
{
    /**
     * @var bool
     */
    protected $disableCache = false;

    /**
     * @param bool $disableCache
     * @return $this
     */
    public function setDisableCache($disableCache)
    {
        $this->disableCache = $disableCache;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if ($this->disableCache === false && $this->checkCache()) {
            return $this->getCachedResult();
        }

        $result = $this->getUncachedResult();

        $cache = $this->getCache($this->getCacheName());
        $item = $cache->getItem($this->getCacheKey());
        $item->set($result);
        $cache->save($item);

        return $result;
    }

    /**
     * @return mixed
     */
    protected function getCachedResult()
    {
        $cache = $this->getCache($this->getCacheName());
        $item = $cache->getItem($this->getCacheKey());

        return $item->get();
    }

    /**
     * @return bool
     */
    protected function checkCache()
    {
        $cache = $this->getCache($this->getCacheName());
        $item = $cache->getItem($this->getCacheKey());
        $item->get();

        return $item->isHit();
    }

    /**
     * @return string
     */
    abstract protected function getCacheName();

    /**
     * @return string
     */
    abstract protected function getCacheKey();

    /**
     * @return mixed
     */
    abstract protected function getUncachedResult();
}
