<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

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
