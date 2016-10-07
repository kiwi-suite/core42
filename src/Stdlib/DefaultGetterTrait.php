<?php
namespace Core42\Stdlib;

use Core42\Command\CommandInterface;
use Core42\Command\Form\FormCommand;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Selector\SelectorInterface;
use Psr\Cache\CacheItemPoolInterface;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

trait DefaultGetterTrait
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param string $commandName
     * @return CommandInterface
     */
    protected function getCommand($commandName)
    {
        return $this->getServiceManager()->get('Command')->get($commandName);
    }

    /**
     * @param string $formName
     * @return Form
     */
    protected function getForm($formName)
    {
        return $this->getServiceManager()->get("Form")->get($formName);
    }

    /**
     * @param string $selectorName
     * @return SelectorInterface
     */
    protected function getSelector($selectorName)
    {
        return $this->getServiceManager()->get('Selector')->get($selectorName);
    }

    /**
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    protected function getTableGateway($tableGatewayName)
    {
        return $this->getServiceManager()->get('TableGateway')->get($tableGatewayName);
    }

    /**
     * @return FormCommand
     */
    protected function getFormCommand()
    {
        return $this->getCommand(FormCommand::class);
    }

    /**
     * @param string $cacheName
     * @return CacheItemPoolInterface
     */
    protected function getCache($cacheName)
    {
        return $this->getServiceManager()->get('Cache')->get($cacheName);
    }
}
