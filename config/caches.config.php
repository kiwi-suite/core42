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

namespace Core42;

use Stash\Driver\Composite;
use Stash\Driver\Ephemeral;
use Stash\Driver\FileSystem;

return [
    'cache' => [
        'caches' => [],
        'drivers' => [
            'ephemeral' => [
                'driver' => Ephemeral::class,
                'options' => [],
            ],
            'filesystem' => [
                'driver' => FileSystem::class,
                'options' => [
                    'path' => 'data/cache',
                ],
            ],

            'production' => [
                'driver' => Composite::class,
                'options' => [
                    'drivers' => 'ephemeral, filesystem',
                ],
            ],

            'development' => [
                'driver' => Composite::class,
                'options' => [
                    'drivers' => 'ephemeral',
                ],
            ],
        ],
    ],
];
