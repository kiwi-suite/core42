<?php
namespace Core42;

use Stash\Driver\Ephemeral;

return [
    'cache' => [
        'caches' => [],
        'drivers' => [
            'ephemeral' => [
                'driver' => Ephemeral::class,
                'options' => [],
            ],
        ],
    ],
];
