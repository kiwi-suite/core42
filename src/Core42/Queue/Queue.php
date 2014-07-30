<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Queue;

use Core42\Command\CommandInterface;
use Core42\Command\Service\CommandPluginManager;
use Core42\Queue\Adapter\AdapterInterface;
use Core42\Queue\Service\AdapterPluginManager;

class Queue
{
    /**
     * @var AdapterPluginManager
     */
    private $adapterPluginManager;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var CommandPluginManager
     */
    private $commandPluginManager;

    /**
     * @param AdapterPluginManager $adapterPluginManager
     * @param CommandPluginManager $commandPluginManager
     * @param array $options
     */
    public function __construct(
        AdapterPluginManager $adapterPluginManager,
        CommandPluginManager $commandPluginManager,
        array $options = array()
    ) {
        $this->adapterPluginManager = $adapterPluginManager;

        $this->commandPluginManager = $commandPluginManager;

        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->adapter = $this->adapterPluginManager->get($options['name'], $options['params']);
    }

    /**
     * @param CommandInterface $command
     * @return Job
     * @throws \Exception
     */
    protected function getJobFromCommand(CommandInterface $command)
    {
        if (!($command instanceof QueueAwareInterface)) {
            throw new \Exception(sprintf(
                "command '%s' doesn't implement Core42\\Queue\\QueueAwareInterface",
                get_class($command)
            ));
        }

        $job = $command->queueExtract();

        if (!($job instanceof Job)) {
            throw new \Exception("queueExtract doesn't return 'Core42\\Queue\\Job'");
        }

        return $job;
    }

    /**
     * @return null|object
     * @throws \Exception
     */
    public function pop()
    {
        $job = $this->adapter->pop();
        if (!($job instanceof Job)) {
            return null;
        }

        $cmd = $this->commandPluginManager->get($job->getServiceName());
        if (!($cmd instanceof QueueAwareInterface)) {
            throw new \Exception("'%s' doesn't implements the 'Core42\\Queue\\QueueAwareInterface");
        }

        $cmd->queueSetup($job->getParams());

        return $cmd;
    }

    /**
     * @param CommandInterface $command
     * @throws \Exception
     */
    public function push(CommandInterface $command)
    {
        $job = $this->getJobFromCommand($command);
        $this->adapter->push($job);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->adapter->count();
    }
}
