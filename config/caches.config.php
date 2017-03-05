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
