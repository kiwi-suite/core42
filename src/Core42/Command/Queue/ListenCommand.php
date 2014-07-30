<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Queue;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareInterface;
use Core42\Queue\Queue;
use Zend\Console\Request;
use ZF\Console\Route;

class ListenCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string
     */
    private $queue;

    /**
     * @var int
     */
    private $sleep = 3;

    /**
     * @var Queue
     */
    private $queueInstance;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param string $queue
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * @param int $sleep
     * @return $this
     */
    public function setSleep($sleep)
    {
        $this->sleep = (int) $sleep;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (PHP_SAPI != 'cli') {
            throw new \Exception('This command must be called in cli');
        }

        $this->request = $this->getServiceManager()->get('request');

        if (!($this->request instanceof Request)) {
            throw new \Exception('This command must be called in cli');
        }

        $this->queueInstance = $this->getServiceManager()->get($this->queue);

        if (!($this->queueInstance instanceof Queue)) {
            $this->addError('queue', 'Queue isn\'t an instance of \'Core42\Queue\Queue\'');
            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $runDaemon = true;
        while ($runDaemon) {
            if ($this->queueInstance->count()) {
                exec(getcwd() . '/' . $this->request->getScriptName() .' queue-work --queue=' . $this->queue);
            } else {
                sleep($this->sleep);
            }
        }
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setQueue($route->getMatchedParam('queue'));
        $this->setSleep($route->getMatchedParam('sleep'));
    }
}
