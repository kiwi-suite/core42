<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Selector;

use Core42\Command\Migration\AbstractCommand;
use Core42\Db\TableGateway\AbstractTableGateway;
use Psr\Cache\CacheItemPoolInterface;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractSelector implements SelectorInterface
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    final public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        $this->init();
    }

    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     *
     */
    protected function init()
    {

    }

    /**
     * @param string $commandName
     * @return AbstractCommand
     */
    protected function getCommand($commandName)
    {
        return $this->getServiceManager()->get('Command')->get($commandName);
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
     * @param string $selectorName
     * @return SelectorInterface
     */
    protected function getSelector($selectorName)
    {
        return $this->getServiceManager()->get('Selector')->get($selectorName);
    }

    /**
     * @param string $cacheName
     * @return CacheItemPoolInterface
     */
    public function getCache($cacheName)
    {
        return $this->getServiceManager()->get('Cache')->get($cacheName);
    }
}
