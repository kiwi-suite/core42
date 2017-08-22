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
