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
use Zend\Form\Form;

/**
 * Class AbstractActionController
 * @package Core42\Mvc\Controller
 *
 * @method \Core42\Mvc\Controller\Plugin\Permission permission(string $serviceName)
 */
class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * @param string $commandName
     * @return AbstractCommand
     */
    public function getCommand($commandName)
    {
        return $this->getServiceLocator()->get('Command')->get($commandName);
    }

    /**
     * @param string $formName
     * @return Form
     */
    public function getForm($formName)
    {
        return $this->getServiceLocator()->get("Form")->get($formName);
    }

    /**
     * @param string $selectQueryName
     * @return AbstractSelectQuery
     */
    public function getSelectQuery($selectQueryName)
    {
        return $this->getServiceLocator()->get('SelectQuery')->get($selectQueryName);
    }

    /**
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    public function getTableGateway($tableGatewayName)
    {
        return $this->getServiceLocator()->get('TableGateway')->get($tableGatewayName);
    }

    /**
     * @return FormCommand
     */
    public function getFormCommand()
    {
        return $this->getCommand('Core42\Form\Form');
    }
}
