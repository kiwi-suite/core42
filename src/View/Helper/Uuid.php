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


namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Uuid extends AbstractHelper
{
    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param null $node
     * @param null $clockSeq
     * @return string
     */
    public function uuid1($node = null, $clockSeq = null)
    {
        return \Ramsey\Uuid\Uuid::uuid1($node, $clockSeq)->toString();
    }

    /**
     * @param $ns
     * @param $name
     * @return string
     */
    public function uuid3($ns, $name)
    {
        return \Ramsey\Uuid\Uuid::uuid3($ns, $name)->toString();
    }

    /**
     * @return string
     */
    public function uuid4()
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }

    /**
     * @param $ns
     * @param $name
     * @return string
     */
    public function uuid5($ns, $name)
    {
        return \Ramsey\Uuid\Uuid::uuid5($ns, $name)->toString();
    }
}
