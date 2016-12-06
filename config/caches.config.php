<?php
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
                    'path' => 'data/cache'
                ],
            ],

            'production' => [
                'driver' => Composite::class,
                'options' => [
                    'drivers' => 'ephemeral, filesystem',
                ]
            ],

            'development' => [
                'driver' => Composite::class,
                'options' => [
                    'drivers' => 'ephemeral',
                ]
            ],
        ],
    ],
];
