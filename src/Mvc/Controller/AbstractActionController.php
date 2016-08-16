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
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class AbstractActionController
 * @package Core42\Mvc\Controller
 *
 * @method \Core42\Mvc\Controller\Plugin\Permission permission(string $serviceName)
 */
class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * AbstractActionController constructor.
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @deprecated
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->getServiceManager();
    }

    /**
     * @param string $commandName
     * @return AbstractCommand
     */
    public function getCommand($commandName)
    {
        return $this->getServiceManager()->get('Command')->get($commandName);
    }

    /**
     * @param string $formName
     * @return Form
     */
    public function getForm($formName)
    {
        return $this->getServiceManager()->get("Form")->get($formName);
    }

    /**
     * @param string $selectorName
     * @return SelectorInterface
     */
    public function getSelector($selectorName)
    {
        return $this->getServiceManager()->get('Selector')->get($selectorName);
    }

    /**
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    public function getTableGateway($tableGatewayName)
    {
        return $this->getServiceManager()->get('TableGateway')->get($tableGatewayName);
    }

    /**
     * @return FormCommand
     */
    public function getFormCommand()
    {
        return $this->getCommand(FormCommand::class);
    }
}
