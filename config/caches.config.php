<?php
namespace Core42;

return [
    'caches' => [
        'Cache\Intern' => [
            'adapter' => [
                'name' => 'filesystem',
                'options' => [
                    'cache_dir'      => 'data/cache/',
                    'namespace'      => 'cache_intern',
                    'dirPermission'  => 0770,
                    'filePermission' => 0660,
                    'readable'       => !DEVELOPMENT_MODE,
                    'writable'       => !DEVELOPMENT_MODE,
                ],
            ],
            'plugins' => [
                'Serializer'
            ],
        ],
    ],
];
