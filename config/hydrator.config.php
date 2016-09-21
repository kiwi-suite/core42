<?php
namespace Core42;

use Core42\Hydrator\BaseHydrator;
use Core42\Hydrator\Service\BaseHydratorFactory;
use Core42\Hydrator\Strategy\BooleanStrategy;
use Core42\Hydrator\Strategy\DateStrategy;
use Core42\Hydrator\Strategy\DateTimeStrategy;
use Core42\Hydrator\Strategy\DateTimeTimestampStrategy;
use Core42\Hydrator\Strategy\FloatStrategy;
use Core42\Hydrator\Strategy\IntegerStrategy;
use Core42\Hydrator\Strategy\JsonStrategy;
use Core42\Hydrator\Strategy\StringStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'hydrator_strategies' => [
        'factories' => [
            BooleanStrategy::class              => InvokableFactory::class,
            DateStrategy::class                 => InvokableFactory::class,
            DateTimeStrategy::class             => InvokableFactory::class,
            DateTimeTimestampStrategy::class    => InvokableFactory::class,
            FloatStrategy::class                => InvokableFactory::class,
            IntegerStrategy::class              => InvokableFactory::class,
            StringStrategy::class               => InvokableFactory::class,
            JsonStrategy::class                 => InvokableFactory::class,
        ],
        'aliases' => [
            'Boolean'                           => BooleanStrategy::class,
            'Date'                              => DateStrategy::class,
            'DateTime'                          => DateTimeStrategy::class,
            'DateTimeTimestamp'                 => DateTimeTimestampStrategy::class,
            'Float'                             => FloatStrategy::class,
            'Integer'                           => IntegerStrategy::class,
            'String'                            => StringStrategy::class,
            'Json'                              => JsonStrategy::class,
        ],
    ],

    'hydrators' => [
        'factories' => [
            BaseHydrator::class                 => BaseHydratorFactory::class,
        ],
    ]
];
