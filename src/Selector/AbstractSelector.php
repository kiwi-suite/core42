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

namespace Core42\Selector;

use Core42\Stdlib\DefaultGetterTrait;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractSelector implements SelectorInterface
{
    use DefaultGetterTrait;

    /**
     * @param ServiceManager $serviceManager
     */
    final public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        $this->init();
    }

    /**
     *
     */
    protected function init()
    {
    }
}
