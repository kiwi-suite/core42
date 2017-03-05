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


namespace Core42\View\Helper;

use Zend\Http\PhpEnvironment\Request;
use Zend\Router\Http\RouteMatch;
use Zend\View\Helper\AbstractHelper;

class Params extends AbstractHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * Params constructor.
     * @param Request $request
     * @param RouteMatch $routeMatch
     */
    public function __construct(Request $request, RouteMatch $routeMatch = null)
    {
        $this->request = $request;

        $this->routeMatch = $routeMatch;
    }

    /**
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function __invoke($param = null, $default = null)
    {
        if ($param === null) {
            return $this;
        }

        return $this->fromRoute($param, $default);
    }

    /**
     * @param  string                  $name
     * @param  mixed                   $default
     * @return array|\ArrayAccess|null
     */
    public function fromFiles($name = null, $default = null)
    {
        if ($name === null) {
            return $this->request->getFiles($name, $default)->toArray();
        }

        return $this->request->getFiles($name, $default);
    }

    /**
     * @param  string                                 $header
     * @param  mixed                                  $default
     * @return null|\Zend\Http\Header\HeaderInterface
     */
    public function fromHeader($header = null, $default = null)
    {
        if ($header === null) {
            return $this->request->getHeaders($header, $default)->toArray();
        }

        return $this->request->getHeaders($header, $default);
    }

    /**
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromPost($param = null, $default = null)
    {
        if ($param === null) {
            return $this->request->getPost($param, $default)->toArray();
        }

        return $this->request->getPost($param, $default);
    }

    /**
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromQuery($param = null, $default = null)
    {
        if ($param === null) {
            return $this->request->getQuery($param, $default)->toArray();
        }

        return $this->request->getQuery($param, $default);
    }

    /**
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromRoute($param = null, $default = null)
    {
        if (empty($this->routeMatch)) {
            return '';
        }
        if ($param === null) {
            return $this->routeMatch->getParams();
        }

        return $this->routeMatch->getParam($param, $default);
    }
}
