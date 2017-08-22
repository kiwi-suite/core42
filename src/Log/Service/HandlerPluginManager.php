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


namespace Core42\Log\Service;

use Monolog\Handler\HandlerInterface;
use Zend\ServiceManager\AbstractPluginManager;

class HandlerPluginManager extends AbstractPluginManager
{
    /**
     * {@inheritdoc}
     */
    public function __construct($configInstanceOrParentLocator, array $config)
    {
        parent::__construct($configInstanceOrParentLocator, $config['handler_manager']);

        $this->addAbstractFactory(new AbstractHandlerFactory($config));
    }

    /**
     * @param string $name
     * @param array|null $options
     * @return HandlerInterface
     */
    public function build($name, array $options = null)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        $handler = parent::build($name, $options);
        $this->services[$name] = $handler;

        return $handler;
    }
}
