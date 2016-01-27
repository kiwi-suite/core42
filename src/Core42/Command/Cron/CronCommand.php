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

        $p = [];
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

            $cmd = 'php module/core42/bin/fruit cron-wrapper ' . escapeshellarg($task->getName()) .  ' 2>&1 &';
            //echo $cmd . "\n";

            $descriptors = [];
            if (!empty($task->getLogfile())) {
                $descriptors[1] = ['file', $task->getLogfile(), 'a'];
                $descriptors[2] = ['file', $task->getLogfile(), 'a'];
            }
            $p[] = proc_open ($cmd, $descriptors, $pipes);
        }

        foreach ($p as $_p) {
            proc_close($_p);
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

        $this->silent = $route->getMatchedParam('silent');

        $name = $route->getMatchedParam('name');
        if (!empty($name)) {
            $this->taskName = $name;
        }
    }
}
