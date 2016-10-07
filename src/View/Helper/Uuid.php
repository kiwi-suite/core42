<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
    public static function uuid3($ns, $name)
    {
        return \Ramsey\Uuid\Uuid::uuid3($ns, $name)->toString();
    }

    /**
     * @return string
     */
    public static function uuid4()
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }

    /**
     * @param $ns
     * @param $name
     * @return string
     */
    public static function uuid5($ns, $name)
    {
        return \Ramsey\Uuid\Uuid::uuid5($ns, $name)->toString();
    }
}
