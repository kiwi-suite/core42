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


namespace Core42\Command\Service;

use Core42\Command\CommandInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class CommandPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = CommandInterface::class;

    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * @param string $name
     * @param array|null $options
     * @return mixed
     */
    public function get($name, array $options = null)
    {
        if (!$this->has($name)) {
            if (!$this->autoAddInvokableClass || !\class_exists($name)) {
                throw new ServiceNotFoundException(\sprintf(
                    'A plugin by the name "%s" was not found in the plugin manager %s',
                    $name,
                    \get_class($this)
                ));
            }

            $this->setFactory($name, CommandFactory::class);
        }

        return parent::get($name, $options);
    }
}
