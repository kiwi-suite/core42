<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Cron;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Model\Cron;
use Core42\TableGateway\CronTableGateway;
use Zend\Db\Sql\Where;
use Zend\Log\Logger;
use Zend\Log\Formatter\Simple as SimpleFormatter;
use ZF\Console\Route;

class CronCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $ignoreLock = false;

    /**
     * @var string|null
     */
    protected $taskName = null;

    /**
     * @var string|null
     */
    protected $group = null;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @return mixed
     */
    protected function execute()
    {
        set_time_limit(0);

        $this->consoleOutput("starting cron");

        $now = new \DateTime();

        /* @var CronTableGateway $timedTaskTableGateway */
        $timedTaskTableGateway = $this->getTableGateway(CronTableGateway::class);

        $where = new Where();
        if (empty($this->taskName)) {
            $where->equalTo('status', Cron::STATUS_AUTO)
                ->nest()
                ->lessThanOrEqualTo('nextRun', $now->format('Y-m-d H:i:s'))
                ->or
                ->isNull('nextRun')
                ->unnest();

        } else {
            $where->equalTo('name', $this->taskName);
        }

        if (!$this->ignoreLock && empty($this->taskName)) {
            $where->isNull('lock');
        }

        if (!empty($this->group)) {
            $where->equalTo("group", $this->group);
        }

        $tasks = $timedTaskTableGateway->select($where);

        if (!empty($this->taskName)) {
            if ($tasks->count() == 0) {
                $this->consoleOutput(sprintf('<error>cron task "%s" not found</error>', $this->taskName));
            }
        } else {
            $this->consoleOutput(sprintf("<info>%d tasks found for execution</info>", $tasks->count()));
        }

        $p = [];
        foreach ($tasks as $task) {
            /* @var Cron $task */

            if ($task->getStatus() == Cron::STATUS_DISABLED) {
                $this->consoleOutput(
                    sprintf("<warning>trying to execute disabled task '%s'</warning>", $task->getName())
                );
                continue;
            }

            if ($task->getLock() !== null && !$this->ignoreLock) {
                $this->consoleOutput(
                    sprintf("<warning>trying to execute locked task '%s'</warning>", $task->getName())
                );
                continue;
            }

            $cmd = PHP_BINARY . ' vendor/fruit42/core42/bin/fruit cron-wrapper ' . escapeshellarg($task->getName()) .  ' 2>&1 &';

            $descriptors = [];

            $pipes = [];
            $p[] = proc_open($cmd, $descriptors, $pipes);
        }

        foreach ($p as $proc) {
            proc_close($proc);
        }
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->ignoreLock = $route->getMatchedParam('ignorelock');
        if (!$this->ignoreLock) {
            $this->ignoreLock = $route->getMatchedParam('i');
        }

        $name = $route->getMatchedParam('name');
        if (!empty($name)) {
            $this->taskName = $name;
        }

        $group = $route->getMatchedParam('group');
        if (!empty($group)) {
            $this->group = $group;
        }
    }
}
