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
use Cron\CronExpression;
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
    protected $silent = false;

    /**
     * @var bool
     */
    protected $ignoreLock = false;

    /**
     * @var string|null
     */
    protected $taskName = null;

    /**
     *
     */
    protected function preExecute()
    {
        $config = $this->getServiceManager()->get('Config');

        if (isset($config['cron']['logger'])) {
            $this->logger = $this->getServiceManager()->get($config['cron']['logger']);
        } else {
            $this->logger = new Logger([
                'writers' => ['null'],
            ]);
        }

        if (!$this->silent) {
            $writer = new \Zend\Log\Writer\Stream('php://output');
            $writer->setFormatter(new SimpleFormatter('%priorityName%: %message% %extra%'));
            $this->logger->addWriter($writer);
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        set_time_limit(0);

        $this->logger->info('starting cron run');

        $now = new \DateTime();

        /* @var CronTableGateway $timedTaskTableGateway */
        $timedTaskTableGateway = $this->getTableGateway('Core42\Cron');

        $where = new Where();
        if (empty($this->taskName)) {
            $where->equalTo('status', Cron::STATUS_AUTO)
                ->nest()
                ->lessThanOrEqualTo('next_run', $now->format('Y-m-d H:i:s'))
                ->or
                ->isNull('next_run')
                ->unnest();

        } else {
            $where->equalTo('name', $this->taskName);
        }

        if (!$this->ignoreLock && empty($this->taskName)) {
            $where->isNull('lock');
        }

        $tasks = $timedTaskTableGateway->select($where);

        if (!empty($this->taskName)) {
            if ($tasks->count() == 0) {
                $this->logger->warn(sprintf('cron task "%s" not found', $this->taskName));
            }
        } else {
            $this->logger->debug(sprintf("%d tasks found for execution", $tasks->count()));
        }

        foreach ($tasks as $task) {
            /* @var Cron $task */

            if ($task->getStatus() == Cron::STATUS_DISABLED) {
                $this->logger->warn('trying to execute disabled task ' . $task->getName());
                continue;
            }

            if ($task->getLock() !== null && !$this->ignoreLock) {
                $this->logger->warn('trying to execute locked task ' . $task->getName());
                continue;
            }

            $params = [];
            $taskParams = $task->getParameters();
            if (!empty($taskParams)) {
                $taskParams = json_decode($taskParams, true);
                if ($taskParams !== null) {
                    $params = $taskParams;
                    unset($taskParams);
                }
            }

            if (array_key_exists('lastrun', $params)) {
                $params['lastrun'] = ($task->getLastRun() instanceof \DateTime) ? $task->getLastRun()->getTimestamp() : 0;
            }

            $task->setLastRun(new \DateTime());
            $task->setLock(new \DateTime());
            $timedTaskTableGateway->update($task);

            try {
                $cronExpression = CronExpression::factory($task->getCronInterval());
            } catch(\InvalidArgumentException $e) {
                $this->logger->warn(sprintf('cron task %s: unable to parse cron expression! (%s)', $task->getName(), $task->getCronInterval()));
                continue;
            }

            $this->logger->info(sprintf("cron task %s started", $task->getName()));

            if ($this->runCommand($task->getCommand(), $params, $output, $returnVar)) {
                $this->logger->info(sprintf("cron task %s successful finished", $task->getName()));
            } else {
                $this->logger->err(sprintf('cron task %s exited with status code %d', $task->getName(), $returnVar));
            }

            $logfile = $task->getLogfile();
            if (!empty($logfile) && is_writable($logfile)) {
                file_put_contents($logfile, $output, FILE_APPEND | LOCK_EX);
            }

            $nextRun = $cronExpression->getNextRunDate();

            $task->setNextRun($nextRun);
            $task->setLock(null);
            $timedTaskTableGateway->update($task);
        }
    }

    /**
     * @param string $command
     * @param array $params
     * @param null $output
     * @param int $returnVar
     * @return bool
     */
    protected function runCommand($command, $params, &$output = null, &$returnVar = 0)
    {
        $cmd = 'php module/core42/bin/fruit';
        $cmd .= " {$command}";
        foreach ($params as $name => $value) {
            if ($name == $value || $value === null) {
                $cmd .= " --{$name}";
            } else {
                $value = escapeshellarg($value);
                $cmd .= " --{$name} {$value}";
            }
        }

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            return false;
        }

        return true;
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

        $this->silent = $route->getMatchedParam('silent');

        $name = $route->getMatchedParam('name');
        if (!empty($name)) {
            $this->taskName = $name;
        }
    }
}
