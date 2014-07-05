<?php
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

    public function __construct()
    {
    }

    public function __invoke(Route $route, AdapterInterface $console)
    {
        $cliConfig = $this->serviceManager->get('config');
        $cliConfig = $cliConfig['cli'];

        $commandName = null;

        foreach ($cliConfig as $name => $info) {
            if (!array_key_exists('name', $info) && is_string($name)) {
                $info['name'] = $name;
            }

            if ($route->getName() == $info['name']) {
                $commandName = (array_key_exists('command-name', $info)) ? $info['command-name'] : null;
                break;
            }
        }

        if (empty($commandName)) {
            $console->writeLine('"command-name" cant be empty', ColorInterface::RED);
            return;
        }

        /** @var CommandInterface $command */
        $command = $this->serviceManager->get('Command')->get($commandName);
        if (!($command instanceof ConsoleAwareInterface)) {
            $console->writeLine('command must implement interface "Core42\Command\CommandAwareInterface"', ColorInterface::RED);
            return;
        }
        $command->consoleSetup($route);
        $command->run();

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
