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

namespace Core42\Hydrator;

use Core42\Hydrator\Strategy\Service\StrategyPluginManager;
use Core42\Model\ModelInterface;
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\Strategy\StrategyInterface;

class BaseHydrator extends ArraySerializable
{
    /**
     * @var bool
     */
    protected $allowNull = true;

    /**
     * @var StrategyPluginManager
     */
    protected $strategyPluginManager;

    /**
     * @param StrategyPluginManager $strategyPluginManager
     */
    public function __construct(StrategyPluginManager $strategyPluginManager)
    {
        $this->strategyPluginManager = $strategyPluginManager;
        parent::__construct();
    }

    /**
     * @param array $data
     * @return array
     */
    public function extractArray(array $data)
    {
        foreach ($data as $name => $value) {
            $data[$name] = $this->extractValue($name, $value);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function hydrateArray(array $data)
    {
        foreach ($data as $name => $value) {
            $data[$name] = $this->hydrateValue($name, $value);
        }

        return $data;
    }

    /**
     * @param $allowNull
     */
    public function allowNull($allowNull)
    {
        $this->allowNull = (bool) $allowNull;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param null|ModelInterface $object
     * @return mixed
     */
    public function extractValue($name, $value, $object = null)
    {
        if ($this->allowNull === false || ($this->allowNull === true && $value !== null)) {
            return parent::extractValue($name, $value, $object);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param null|array $data
     * @return mixed
     */
    public function hydrateValue($name, $value, $data = null)
    {
        if ($this->allowNull === false || ($this->allowNull === true && $value !== null)) {
            return parent::hydrateValue($name, $value, $data);
        }

        return $value;
    }

    /**
     * @param array $strategies
     * @throws \Exception
     */
    public function addStrategies(array $strategies)
    {
        foreach ($strategies as $name => $strategy) {
            $this->addStrategy($name, $this->getStrategyObject($strategy));
        }
    }

    /**
     * @param $strategy
     * @throws \Exception
     * @return StrategyInterface
     */
    public function getStrategyObject($strategy)
    {
        if ($strategy instanceof StrategyInterface) {
            return $strategy;
        }

        if (\is_string($strategy)) {
            $strategy = $this->strategyPluginManager->get($strategy);
        }

        if (!($strategy instanceof StrategyInterface)) {
            throw new \Exception('invalid strategy');
        }

        return $strategy;
    }
}
