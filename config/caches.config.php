<?php
namespace Core42;

return array(
    'caches' => array(
        'Cache\Intern' => array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => 'data/cache/',
                    'namespace' => 'cache_intern'
                ),
            ),
            'plugins' => array(
                'Serializer'
            ),
        ),
        'Cache\InternStatic' => array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => 'data/cache/',
                    'namespace' => 'cache_internstatic'
                ),
            ),
            'plugins' => array(
                'Serializer'
            ),
        ),
    ),
);
