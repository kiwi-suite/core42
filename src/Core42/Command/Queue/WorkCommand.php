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
use ZF\Console\Route;

class WorkCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string|Queue
     */
    private $queue;

    /**
     * @param string|Queue $queue
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (is_string($this->queue)) {
            $this->queue = $this->getServiceManager()->get($this->queue);
        }

        if (!($this->queue instanceof Queue)) {
            $this->addError('queue', 'Queue isn\'t an instance of \'Core42\Queue\Queue\'');
            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $cmd = $this->queue->pop();
        if ($cmd === null) {
            return;
        }

        $cmd->run();
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setQueue($route->getMatchedParam('queue'));
    }
}
