<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Mvc\Controller;

use Core42\Command\AbstractCommand;
use Core42\Command\Form\FormCommand;
use Core42\Db\SelectQuery\AbstractSelectQuery;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Selector\SelectorInterface;
use Core42\Stdlib\DefaultGetterTrait;
use Psr\Cache\CacheItemPoolInterface;
use Zend\Form\Form;
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
