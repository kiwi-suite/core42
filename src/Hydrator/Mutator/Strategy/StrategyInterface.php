<?php
namespace Core42\Hydrator\Mutator\Strategy;

interface StrategyInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value);
}
