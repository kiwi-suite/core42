<?php
namespace Core42\Hydrator\Mutator\Strategy;

class DefaultStrategy implements StrategyInterface
{

    /**
     * @param mixed $value
     * @param array $spec
     * @return mixed
     */
    public function hydrate($value, array $spec = [])
    {
        return $value;
    }
}
