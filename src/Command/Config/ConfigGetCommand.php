<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Command\Config;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class ConfigGetCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var string
     */
    protected $requestedKey;

    /**
     * @var array
     */
    protected $requestedConfig;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @param string $requestedKey
     * @return $this
     */
    public function setRequestedKey($requestedKey)
    {
        $this->requestedKey = $requestedKey;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        $this->requestedConfig = $this->getServiceManager()->get('config');

        if (!empty($this->requestedKey)) {
            $keyParts = \explode(".", $this->requestedKey);

            foreach ($keyParts as $part) {
                if (!\array_key_exists($part, $this->requestedConfig)) {
                    $this->addError("key", \sprintf("key '%s' doesn't exist in config", $this->requestedKey));

                    return;
                }

                $this->requestedConfig = $this->requestedConfig[$part];
            }
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->displayConfig($this->requestedConfig, 0);
    }

    /**
     * @param mixed $config
     * @param int $level
     */
    protected function displayConfig($config, $level)
    {
        $padding = \str_repeat(" ", $level * 4);
        if (!\is_array($config)) {
            $this->consoleOutput($padding . \var_export($config, true));

            return;
        }

        foreach ($config as $key => $value) {
            if (\is_array($value)) {
                $this->consoleOutput($padding . '[' . $key . '] =>');
                $this->displayConfig($value, $level + 1);
                continue;
            }

            $this->consoleOutput($padding . '[' . $key . '] => ' . \var_export($value, true));
        }
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->setRequestedKey($route->getMatchedParam("key", null));
    }
}
