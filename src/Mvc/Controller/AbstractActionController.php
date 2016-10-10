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
