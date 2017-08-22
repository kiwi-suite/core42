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

namespace Core42\Hydrator\Mutator;

use Core42\Hydrator\Mutator\Service\StrategyPluginManager;
use Core42\Hydrator\Mutator\Strategy\StrategyInterface;

class Mutator
{
    /**
     * @var StrategyPluginManager
     */
    protected $strategyPluginManager;

    /**
     * Mutator constructor.
     * @param StrategyPluginManager $strategyPluginManager
     */
    public function __construct(StrategyPluginManager $strategyPluginManager)
    {
        $this->strategyPluginManager = $strategyPluginManager;
    }

    /**
     * @param array $data
     * @param array $specification
     * @return array
     */
    public function hydrate(array $data, array $specification)
    {
        $newData = [];
        $specification = $this->normalizeSpecification($specification);

        foreach ($specification as $name => $spec) {
            $type = $spec['type'];
            if (!\array_key_exists($name, $data)) {
                $newData[$name] = null;
                continue;
            }

            /** @var StrategyInterface $strategy */
            $strategy = $this->strategyPluginManager->get("default");
            if ($this->strategyPluginManager->has($type)) {
                $strategy = $this->strategyPluginManager->get($type);
            }

            $newData[$name] = $strategy->hydrate($data[$name], $spec);
        }


        return $newData;
    }

    /**
     * @param $specification
     * @throws \Exception
     * @return array
     */
    protected function normalizeSpecification($specification)
    {
        $normalized = [];

        foreach ($specification as $spec) {
            if (empty($spec['name'])) {
                throw new \Exception("invalid specification. missing key 'name'");
            }
            if (empty($spec['type'])) {
                throw new \Exception("invalid specification. missing key 'type'");
            }
            $normalized[$spec['name']] = $spec;
        }

        return $normalized;
    }
}
