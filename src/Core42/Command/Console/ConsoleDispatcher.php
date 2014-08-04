<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Console;

use Core42\Command\CommandInterface;
use Core42\Command\ConsoleAwareInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZF\Console\Route;

class ConsoleDispatcher implements ServiceManagerAwareInterface
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
        if (!($command instanceof ConsoleAwareInterface)) {
            $console->writeLine('command must implement interface "Core42\Command\ConsoleAwareInterface"', ColorInterface::RED);

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
            if (!array_key_exists('name', $info) && is_string($name)) {
                $info['name'] = $name;
            }

            if ($routeName == $info['name']) {
                $commandName = (array_key_exists('command-name', $info)) ? $info['command-name'] : null;
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
            foreach ($errors as $_error) {
                foreach ($_error as $msg) {
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
