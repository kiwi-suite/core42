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

namespace Core42\Command;

use Core42\Db\Transaction\TransactionManager;
use Core42\Stdlib\DefaultGetterTrait;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractCommand implements CommandInterface
{
    use DefaultGetterTrait;

    /**
     * @var \Exception|null
     */
    protected $commandException;

    /**
     * @var bool
     */
    protected $throwCommandExceptions = true;

    /**
     * @var bool
     */
    private $dryRun = false;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var bool
     */
    protected $transaction = true;

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
     * @param bool $dryRun
     * @return \Core42\Command\AbstractCommand
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = (bool) $dryRun;

        return $this;
    }

    /**
     * @param  bool                            $enable
     * @return \Core42\Command\AbstractCommand
     */
    final public function enableThrowExceptions($enable)
    {
        $this->throwCommandExceptions = (bool) $enable;

        return $this;
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
                if ($this->transaction === true) {
                    $result = $this
                        ->getServiceManager()
                        ->get(TransactionManager::class)
                        ->transaction(function () {
                            return $this->execute();
                        });
                } else {
                    $result = $this->execute();
                }

                $this->postExecute();
            }
        } catch (\Exception $e) {
            $this->commandException = $e;
            $this->shutdown();
            if ($this->throwCommandExceptions === true) {
                throw $e;
            }
        } finally {
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
            $this->errors[$name] = [];
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
        return count($this->errors) > 0;
    }

    /**
     * @return \Exception|null
     */
    final public function getException()
    {
        return $this->commandException;
    }
}
