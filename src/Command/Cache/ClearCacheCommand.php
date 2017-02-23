<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Command\Cache;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Zend\Stdlib\ArrayUtils;
use ZF\Console\Route;

class ClearCacheCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @var array
     */
    protected $cacheNames = [];

    /**
     * @param array $cacheNames
     * @return $this
     */
    public function setCacheNames(array $cacheNames)
    {
        $this->cacheNames = $cacheNames;

        return $this;
    }

    public function setCacheName($cacheName)
    {
        if ($cacheName == "*") {
            $cacheConfig = $this->getServiceManager()->get('config')['cache']['caches'];
            $this->setCacheNames(ArrayUtils::merge(['app-cache'], \array_keys($cacheConfig)));

            return $this;
        }

        $this->setCacheNames([$cacheName]);

        return $this;
    }

    /**
     *
     */
    protected function execute()
    {
        foreach ($this->cacheNames as $cache) {
            if ($cache === "app-cache") {
                $this->consoleOutput("<info>{$cache}</info> cleared");
                continue;
            }
            $this->getCache($cache)->clear();
            $this->consoleOutput("<info>{$cache}</info> cleared");
        }
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $cache = $route->getMatchedParam("cache");
        if (!empty($cache)) {
            $this->setCacheName($cache);
        }

        if ($route->getMatchedParam('all') || $route->getMatchedParam('a')) {
            $this->setCacheName("*");
        }
    }
}
