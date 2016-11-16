<?php
namespace Core42\Hydrator\Mutator\Strategy;

interface StrategyInterface
{
    /**
     * @param mixed $value
     * @param array $spec
     * @return mixed
     */
    public function hydrate($value, array $spec = []);
}
