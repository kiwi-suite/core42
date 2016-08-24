<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
