<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Test\PHPUnit;

use Core42\Db\ResultSet\ResultSet;
use Zend\EventManager\StaticEventManager;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Exception\LogicException;
use Zend\Stdlib\ResponseInterface;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\Mvc\ApplicationInterface
     */
    protected $application;

    /**
     * @var array
     */
    protected $applicationConfig;

    /**
     * Trace error when exception is throwed in application
     * @var bool
     */
    protected $traceError = false;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var ServiceManager
     */
    protected $tableGatewayServiceManager;

    /**
     * Reset the application for isolation
     */
    public function setUp()
    {
        $this->reset();

        $this->setApplicationConfig(
            include 'config/application.config.php'
        );
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);

        $this->tableGatewayServiceManager = $this->serviceManager->get('TableGateway');
        $this->tableGatewayServiceManager->setAllowOverride(true);
    }

    /**
     * Restore params
     */
    protected function tearDown()
    {
        if (true !== $this->traceError) {
            return;
        }

        $exception = $this->getApplication()->getMvcEvent()->getParam('exception');
        if ($exception instanceof \Exception) {
            throw $exception;
        }
    }

    /**
     * Get the trace error flag
     * @return bool
     */
    public function getTraceError()
    {
        return $this->traceError;
    }

    /**
     * Set the trace error flag
     * @param  bool                       $traceError
     * @return $this
     */
    public function setTraceError($traceError)
    {
        $this->traceError = $traceError;

        return $this;
    }

    /**
     * Get the application config
     * @return array the application config
     */
    public function getApplicationConfig()
    {
        return $this->applicationConfig;
    }

    /**
     * Set the application config
     * @param  array                      $applicationConfig
     * @return $this
     * @throws LogicException
     */
    public function setApplicationConfig($applicationConfig)
    {
        if (null !== $this->application && null !== $this->applicationConfig) {
            throw new LogicException(
                'Application config can not be set, the application is already built'
            );
        }

        // do not cache module config on testing environment
        if (isset($applicationConfig['module_listener_options']['config_cache_enabled'])) {
            $applicationConfig['module_listener_options']['config_cache_enabled'] = false;
        }
        $this->applicationConfig = $applicationConfig;

        return $this;
    }

    /**
     * Get the application object
     * @return \Zend\Mvc\Application
     */
    public function getApplication()
    {
        if ($this->application) {
            return $this->application;
        }
        $appConfig = $this->applicationConfig;
        $this->application = Application::init($appConfig);

        $events = $this->application->getEventManager();
        $events->detach($this->application->getServiceManager()->get('SendResponseListener'));

        return $this->application;
    }

    /**
     * Get the service manager of the application object
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getApplicationServiceLocator()
    {
        return $this->getApplication()->getServiceManager();
    }

    /**
     * Get the application request object
     * @return \Zend\Stdlib\RequestInterface
     */
    public function getRequest()
    {
        return $this->getApplication()->getRequest();
    }

    /**
     * Get the application response object
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->getApplication()->getMvcEvent()->getResponse();
    }

    /**
     * Reset the request
     *
     * @return $this
     */
    public function reset($keepPersistence = false)
    {
        // force to re-create all components
        $this->application = null;

        // reset server datas
        if (!$keepPersistence) {
            $_SESSION = [];
            $_COOKIE  = [];
        }

        $_GET     = [];
        $_POST    = [];

        // reset singleton
        StaticEventManager::resetInstance();

        return $this;
    }

    /**
     * Trigger an application event
     *
     * @param  string                                $eventName
     * @return \Zend\EventManager\ResponseCollection
     */
    public function triggerApplicationEvent($eventName)
    {
        $events = $this->getApplication()->getEventManager();
        $event  = $this->getApplication()->getMvcEvent();

        if ($eventName != MvcEvent::EVENT_ROUTE && $eventName != MvcEvent::EVENT_DISPATCH) {
            return $events->trigger($eventName, $event);
        }

        $shortCircuit = function ($r) use ($event) {
            if ($r instanceof ResponseInterface) {
                return true;
            }

            if ($event->getError()) {
                return true;
            }

            return false;
        };

        return $events->trigger($eventName, $event, $shortCircuit);
    }

    /**
     * Assert modules were loaded with the module manager
     *
     * @param array $modules
     */
    public function assertModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_diff($modules, $modulesLoaded);
        if ($list) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf('Several modules are not loaded "%s"', implode(', ', $list))
            );
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Assert modules were not loaded with the module manager
     *
     * @param array $modules
     */
    public function assertNotModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_intersect($modules, $modulesLoaded);
        if ($list) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf('Several modules WAS not loaded "%s"', implode(', ', $list))
            );
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Assert the application exception and message
     *
     * @param $type application exception type
     * @param $message application exception message
     */
    public function assertApplicationException($type, $message = null)
    {
        $exception = $this->getApplication()->getMvcEvent()->getParam('exception');
        if (!$exception) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                'Failed asserting application exception, exception not exist'
            );
        }
        if (true === $this->traceError) {
            // set exception as null because we know and have assert the exception
            $this->getApplication()->getMvcEvent()->setParam('exception', null);
        }
        $this->setExpectedException($type, $message);
        throw $exception;
    }

    /**
     * @param string $name
     * @param object $modelPrototype
     * @param array|null $dataSet
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockTableGateway($name, $modelPrototype, $dataSet = null)
    {
        $typeTableGatewayMock = $this->getMockBuilder('\Core42\Db\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        if (is_array($dataSet)) {
            $resultSet = new ResultSet();
            $resultSet->setObjectPrototype($modelPrototype);
            $resultSet->initialize($dataSet);

            $typeTableGatewayMock
                ->method('select')
                ->willReturn($resultSet);
        }

        $this->tableGatewayServiceManager->setService($name, $typeTableGatewayMock);

        return $typeTableGatewayMock;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $tableGatewayMock
     * @param array $dataSet
     * @param bool $consecutiveCalls
     */
    protected function mockSql(
        \PHPUnit_Framework_MockObject_MockObject $tableGatewayMock,
        $dataSet = [],
        $consecutiveCalls = false
    ) {
        $selectMock = $this->getMockBuilder('\Zend\Db\Sql\Select')
            ->disableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $whereMock = $this->getMockBuilder('Zend\Db\Sql\Where')
            ->disableOriginalConstructor()
            ->getMock();

        $property = new \ReflectionProperty($selectMock, 'where');
        $property->setAccessible(true);
        $property->setValue($selectMock, $whereMock);

        $sqlMock = $this->getMockBuilder('\Zend\Db\Sql\Sql')
            ->disableOriginalConstructor()
            ->getMock();

        $sqlMock
            ->method('select')
            ->willReturn($selectMock);

        $statementMock = $this->getMockBuilder('Zend\Db\Adapter\Driver\Pdo\Statement')
            //->disableOriginalConstructor()
            ->getMock();

        if ($consecutiveCalls) {
            $resultSets = [];
            foreach ($dataSet as $consecutiveResultSet) {
                $resultSet = new \Zend\Db\ResultSet\ResultSet();
                $resultSet->initialize($consecutiveResultSet);

                $resultSets[] = $resultSet;
            }

            $onConsecutiveCalls = call_user_func_array([$this, 'onConsecutiveCalls'], $resultSets);

            $statementMock
                ->method('execute')
                ->will($onConsecutiveCalls);

        } else {
            $resultSet = new \Zend\Db\ResultSet\ResultSet();
            $resultSet->initialize($dataSet);

            $statementMock
                ->method('execute')
                ->willReturn($resultSet);
        }

        $sqlMock
            ->method('prepareStatementForSqlObject')
            ->willReturn($statementMock);


        $tableGatewayMock->expects($this->any())
            ->method('getSql')
            ->willReturn($sqlMock);

    }
}
