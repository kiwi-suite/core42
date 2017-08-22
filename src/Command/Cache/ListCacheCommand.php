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
