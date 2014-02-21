<?php
namespace Core42\Command;

use Core42\ValueManager\ValueManager;
use Zend\ServiceManager\ServiceManager;
use Core42\ServiceManager\ServiceManagerStaticAwareInterface;

abstract class AbstractCommand implements CommandInterface
{
    /**
     *
     * @var ServiceManager
     */
    private static $serviceManager = null;

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
    private $publishToConsole = false;

    /**
     * @var \Core42\ValueManager\ValueManager
     */
    private $valueManager;

    /**
     * @var bool
     */
    private $dryRun = false;

    /**
     *
     */
    final public function __construct(ServiceManager $manager = null)
    {
        if ($manager !== null) {
            self::$serviceManager = $manager;
        }

        $this->valueManager = new ValueManager();

        $this->enableThrowExceptions(true);
        $this->enablePublishToConsole(false);

        $request = $this->getServiceManager()->get("Request");
        if ($this instanceof ConsoleOutputInterface && $request instanceof \Zend\Console\Request) {
            $this->enablePublishToConsole(true);
        }

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
     * @param $enable
     * @return \Core42\Command\AbstractCommand
     */
    final public function enablePublishToConsole($enable)
    {
        $this->publishToConsole = (boolean) $enable;

        return $this;
    }

    /**
     *
     * @param ServiceManager $manager
     */
    public static function setServiceManager(ServiceManager $manager)
    {
        self::$serviceManager = $manager;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     *
     */
    protected function init() {}

    /**
     * @return \Core42\Command\AbstractCommand
     * @throws \Exception
     */
    final public function run()
    {
        try {
            $this->preExecute();

            if (!$this->hasCommandErrors() && $this->dryRun === false) {
                $this->execute();
                $this->postExecute();
            }
        } catch (\Exception $e) {
            $this->commandException = $e;
            if ($this->throwCommandExceptions === true) {
                throw $e;
            }
        }

        if ($this->publishToConsole === true) {
            $this->publishToConsole();
        }

        return $this;
    }

    /**
     *
     */
    protected function preExecute() {}

    /**
     *
     */
    abstract protected function execute();

    /**
     *
     */
    protected function postExecute() {}

    /**
     * @return bool
     */
    final public function hasCommandErrors()
    {
        return $this->valueManager->hasErrors();
    }

    /**
     * @param  array                           $errors
     * @return \Core42\Command\AbstractCommand
     */
    final public function setCommandErrors(array $errors)
    {
        $this->valueManager->setErrors($errors);

        return $this;
    }

    /**
     * @param  string                          $name
     * @param  string                          $error
     * @return \Core42\Command\AbstractCommand
     */
    final public function setCommandError($name, $error)
    {
        $this->valueManager->setError($name, $error);

        return $this;
    }

    /**
     * @return ValueManager
     */
    final public function getValueManager()
    {
        return $this->valueManager;
    }

    /**
     * @return \Exception|null
     */
    final public function getException()
    {
        return $this->commandException;
    }
}
