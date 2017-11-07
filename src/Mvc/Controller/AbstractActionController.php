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

namespace Core42\Mvc\Controller;

use Core42\Stdlib\DefaultGetterTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController
{
    use DefaultGetterTrait;

    /**
     * AbstractActionController constructor.
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @deprecated
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->getServiceManager();
    }
}
