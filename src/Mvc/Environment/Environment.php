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

namespace Core42\Mvc\Environment;

use Zend\Stdlib\RequestInterface;

class Environment
{
    protected static $callback = [];

    /**
     * @var array
     */
    protected $environments = [];

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param \Closure $callback
     */
    public static function register(\Closure $callback)
    {
        self::$callback[] = $callback;
    }

    /**
     * Environment constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->setup();
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *
     */
    protected function setup()
    {
        if (empty(self::$callback)) {
            return;
        }

        foreach (self::$callback as $key => $callback) {
            unset(self::$callback[$key]);
            $callback($this);
        }
    }

    /**
     * @param string $environment
     */
    public function set($environment)
    {
        $this->environments[$environment] = true;
    }

    /**
     * @param string $environment
     */
    public function remove($environment)
    {
        $this->setup();
        unset($this->environments[$environment]);
    }

    /**
     * @param $environment
     * @return bool
     */
    public function is($environment)
    {
        $this->setup();

        return array_key_exists($environment, $this->environments);
    }
}
