<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command;

use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Selector\SelectorInterface;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractCommand implements CommandInterface
{
    /**
     *
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @var \Exception|null
     */
    protected $commandException;

    /**
     * @var bool
     */
    private $throwCommandExceptions = true;

    /**
     * @var bool
     */
    private $dryRun = false;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * @param ServiceManager $serviceManager
     */
    final public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->enableThrowExceptions(true);
        $this->init();
    }

    /**
     * @param boolean $dryRun
     * @return \Core42\Command\AbstractCommand
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = (boolean) $dryRun;

        return $this;
    }

    /**
     * @param  bool                            $enable
     * @return \Core42\Command\AbstractCommand
     */
    final public function enableThrowExceptions($enable)
    {
        $this->throwCommandExceptions = (boolean) $enable;

        return $this;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param array $values
     * @throws \Exception
     */
    public function hydrate(array $values)
    {
        throw new \Exception("hydrate isn't implemented");
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    final public function run()
    {
        $result = null;

        $this->configure();

        try {
            $this->preExecute();

            if (!$this->hasErrors() && $this->dryRun === false) {
                $result = $this->execute();
                $this->postExecute();
            }
        } catch (\Exception $e) {
            $this->commandException = $e;
            $this->shutdown();
            if ($this->throwCommandExceptions === true) {
                throw $e;
            }
        }

        //TODO might be more clean with PHP5.5 finally
        if ($this->commandException === null) {
            $this->shutdown();
        }

        return $result;
    }

    /**
     *
     */
    protected function init()
    {

    }

    /**
     *
     */
    protected function configure()
    {

    }

    /**
     *
     */
    protected function preExecute()
    {

    }

    /**
     * @return mixed
     */
    abstract protected function execute();

    /**
     *
     */
    protected function postExecute()
    {

    }

    /**
     *
     */
    protected function shutdown()
    {

    }

    /**
     * @param string $name
     * @param string $message
     * @return $this
     */
    protected function addError($name, $message)
    {
        if (!array_key_exists($name, $this->errors)) {
            $this->errors[$name] = array();
        }

        $this->errors[$name][] = $message;

        return $this;
    }

    /**
     * @param array $errors
     * @return $this
     */
    protected function addErrors(array $errors)
    {
        foreach ($errors as $name => $listErrors) {
            foreach ($listErrors as $message) {
                $this->addError($name, $message);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (count($this->errors) > 0);
    }

    /**
     * @return \Exception|null
     */
    final public function getException()
    {
        return $this->commandException;
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
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    public function getTableGateway($tableGatewayName)
    {
        return $this->getServiceManager()->get('TableGateway')->get($tableGatewayName);
    }

    /**
     * @param string $selectorName
     * @return SelectorInterface
     */
    public function getSelector($selectorName)
    {
        return $this->getServiceManager()->get('Selector')->get($selectorName);
    }
}
