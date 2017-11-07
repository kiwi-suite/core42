<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Command\Console;

use Core42\Command\CommandInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\ServiceManager\ServiceManager;
use ZF\Console\Route;

class ConsoleDispatcher
{
    /**
     * @var ServiceManager;
     */
    private $serviceManager;

    /**
     * @param Route $route
     * @param AdapterInterface $console
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        $cliConfig = $this->serviceManager->get('config');
        $cliConfig = $cliConfig['cli'];

        $commandName = $this->getCommandName($cliConfig, $route->getName());

        if (empty($commandName)) {
            $console->writeLine('"command-name" cant be empty', ColorInterface::RED);

            return;
        }

        /** @var CommandInterface $command */
        $command = $this->serviceManager->get('Command')->get($commandName);
        $valid = false;

        $class = \get_class($command);
        do {
            if (\in_array('Core42\Command\ConsoleAwareTrait', \class_uses($class))) {
                $valid = true;
                break;
            }
        } while ($class = \get_parent_class($class));

        if (!$valid) {
            $console->writeLine(
                'command must use "Core42\Command\ConsoleAwareTrait"',
                ColorInterface::RED
            );

            return;
        }

        $command->consoleSetup($route);
        $command->run();

        $this->outputErrors($console, $command);
    }

    /**
     * @param array $cliConfig
     * @param string $routeName
     * @return null|string
     */
    protected function getCommandName($cliConfig, $routeName)
    {
        $commandName = null;

        foreach ($cliConfig as $name => $info) {
            if (!\array_key_exists('name', $info) && \is_string($name)) {
                $info['name'] = $name;
            }

            if ($routeName == $info['name']) {
                $commandName = (\array_key_exists('command-name', $info)) ? $info['command-name'] : null;
                break;
            }
        }

        return $commandName;
    }

    /**
     * @param AdapterInterface $console
     * @param CommandInterface $command
     */
    protected function outputErrors(AdapterInterface $console, CommandInterface $command)
    {
        if ($command->hasErrors()) {
            $errors = $command->getErrors();
            foreach ($errors as $error) {
                foreach ($error as $msg) {
                    $console->writeLine($msg, ColorInterface::RED);
                }
            }
        }
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}
