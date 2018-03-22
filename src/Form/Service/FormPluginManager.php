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

namespace Core42\Form\Service;

use Zend\Form\FormInterface;
use Zend\ServiceManager\AbstractPluginManager;

class FormPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = FormInterface::class;

    /**
     * @var FormElementManager
     */
    protected $formElementManager;

    /**
     * FormPluginManager constructor.
     * @param FormElementManager $formElementManager
     */
    public function __construct(FormElementManager $formElementManager)
    {
        $this->formElementManager = $formElementManager;
    }

    /**
     * @param string $name
     * @param array|null $options
     * @return object
     */
    public function get($name, array $options = null)
    {
        return $this->formElementManager->get($name, $options);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return $this->formElementManager->has($name);
    }

    /**
     * @param string $name
     * @param array|null $options
     * @return mixed
     */
    public function build($name, array $options = null)
    {
        return parent::build($name, $options);
    }

    /**
     * @param string|\Zend\ServiceManager\Factory\AbstractFactoryInterface $factory
     * @throws \Exception
     */
    public function addAbstractFactory($factory)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param callable|string|\Zend\ServiceManager\Initializer\InitializerInterface $initializer
     * @throws \Exception
     */
    public function addInitializer($initializer)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $name
     * @param callable|string|\Zend\ServiceManager\Factory\DelegatorFactoryInterface $factory
     * @throws \Exception
     */
    public function addDelegator($name, $factory)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $name
     * @param bool $flag
     * @throws \Exception
     */
    public function setShared($name, $flag)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $name
     * @param null $class
     * @throws \Exception
     */
    public function setInvokableClass($name, $class = null)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $alias
     * @param string $target
     * @throws \Exception
     */
    public function setAlias($alias, $target)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $name
     * @param callable|string|\Zend\ServiceManager\Factory\FactoryInterface $factory
     * @throws \Exception
     */
    public function setFactory($name, $factory)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param string $name
     * @param array|object $service
     * @throws \Exception
     */
    public function setService($name, $service)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }

    /**
     * @param bool $flag
     * @throws \Exception
     */
    public function setAllowOverride($flag)
    {
        throw new \Exception(
            'This is a proxy class for FormElementManager - please use FormElementManager to set services'
        );
    }
}
