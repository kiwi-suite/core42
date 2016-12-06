<?php
namespace Core42;

use Core42\Hydrator\Mutator\Strategy\DateStrategy;
use Core42\Hydrator\Mutator\Strategy\DateTimeStrategy;
use Core42\Hydrator\Mutator\Strategy\DefaultStrategy;
use Core42\Hydrator\Mutator\Strategy\FloatStrategy;
use Core42\Hydrator\Mutator\Strategy\IntegerStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'mutator' => [
        'factories' => [
            DefaultStrategy::class                  => InvokableFactory::class,
            DateStrategy::class                     => InvokableFactory::class,
            DateTimeStrategy::class                 => InvokableFactory::class,
            IntegerStrategy::class                  => InvokableFactory::class,
            FloatStrategy::class                    => InvokableFactory::class,
        ],
        'aliases' => [
            'default'                               => DefaultStrategy::class,
            'date'                                  => DateStrategy::class,
            'dateTime'                              => DateTimeStrategy::class,
            'integer'                               => IntegerStrategy::class,
            'float'                                 => FloatStrategy::class,
        ],
    ],
];
