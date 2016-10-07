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

class Proxy extends AbstractHelper
{
    /**
     * @var mixed
     */
    protected $object;

    /**
     * Proxy constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @param $method
     * @param $attributes
     * @return mixed
     */
    public function __call($method, $attributes)
    {
        return call_user_func_array([$this->object , $method], $attributes);
    }
}
