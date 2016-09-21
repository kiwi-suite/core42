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
use Zend\Log\Logger;
use Zend\Log\Formatter\Simple as SimpleFormatter;
use ZF\Console\Route;

class CronTaskCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $silent = true;

    /**
     * @var string
     */
    protected $taskName;

    /**
     * @var Cron;
     */
    protected $task;

    /**
     * @var CronTableGateway
     */
    protected $cronTableGateway;

    /**
     * @param string $taskName
     * @return $this
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;
        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        $this->cronTableGateway = $this->getTableGateway(CronTableGateway::class);

        if (empty($this->taskName)) {
            $this->addError('name', 'task name is required');
            return;
        }

        $result = $this->cronTableGateway->select(['name' => $this->taskName]);
        if ($result->count() == 1) {
            $this->task = $result->current();
        }

        if ($this->task === null) {
            $this->addError('name', 'unable to find task with name "' . $this->taskName . '"');
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $params = [];
        $taskParams = $this->task->getParameters();
        if (!empty($taskParams)) {
            $taskParams = json_decode($taskParams, true);
            if ($taskParams !== null) {
                $params = $taskParams;
                unset($taskParams);
            }
        }

        if (array_key_exists('lastrun', $params)) {
            $params['lastrun'] = ($this->task->getLastRun() instanceof \DateTime)
                ? $this->task->getLastRun()->getTimestamp()
                : 0;
        }

        $this->task->setLastRun(new \DateTime());
        $this->task->setLock(new \DateTime());
        $this->cronTableGateway->update($this->task);

        try {
            $cronExpression = CronExpression::factory($this->task->getCronInterval());
        } catch (\InvalidArgumentException $e) {
            $this->consoleOutput(
                sprintf("<error>cron task %s: unable to parse cron expression! (%s)</error>"),
                $this->task->getName(),
                $this->task->getCronInterval()
            );

            return;
        }

        $this->consoleOutput(sprintf(
            "<info>cron task %s started</info>",
            $this->task->getName()
        ));

        if ($this->runCommand($this->task->getCommand(), $params, $output, $returnVar)) {
            $this->consoleOutput(sprintf(
                "<info>cron task %s successful finished</info>",
                $this->task->getName()
            ));
        } else {
            $this->consoleOutput(sprintf(
                "<error>cron task %s exited with status code %d</error>",
                $this->task->getName(),
                $returnVar
            ));
        }

        $nextRun = $cronExpression->getNextRunDate();

        $this->task->setNextRun($nextRun);
        $this->task->setLock(null);
        $this->cronTableGateway->update($this->task);

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
        $cmd = PHP_BINARY . ' vendor/fruit42/core42/bin/fruit';
        $cmd .= " {$command}";
        foreach ($params as $name => $value) {
            if ($name == $value || $value === null) {
                $cmd .= " --{$name}";
            } else {
                $value = escapeshellarg($value);
                $cmd .= " --{$name} {$value}";
            }
        }
        $cmd .= " 2>&1";

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

        $name = $route->getMatchedParam('name');
        if (!empty($name)) {
            $this->taskName = $name;
        }
    }
}
