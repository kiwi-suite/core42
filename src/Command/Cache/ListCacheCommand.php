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
use ZF\Console\Route;

class ListCacheCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     *
     */
    protected function execute()
    {
        $caches = ['app-cache' => 'internal'];

        $cacheConfig = $this->getServiceManager()->get('config')['cache']['caches'];
        foreach ($cacheConfig as $name => $spec) {
            $caches[$name] = $spec['driver'];
        }

        foreach ($caches as $name => $cache) {
            $this->consoleOutput("<info>{$name}</info> [driver: {$cache}]");
        }
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
    }
}
