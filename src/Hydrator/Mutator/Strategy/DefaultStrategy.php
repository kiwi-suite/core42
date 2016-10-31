<?php
namespace Core42\Hydrator\Mutator\Strategy;

class DefaultStrategy implements StrategyInterface
{

    /**
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value)
    {
        return $value;
    }
}
