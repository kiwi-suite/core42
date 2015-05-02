<?php
namespace Core42;

return [
    'log' => [
        'Log\Dev' => [
            'writers' => [
                [
                    'name' => 'chromephp',
                    'options' => [
                        'filters' => [
                            'console' => [
                                'name'      => 'suppress',
                                'options'   => [
                                    'suppress' => (PHP_SAPI === 'cli')
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'name' => 'firephp',
                    'options' => [
                        'filters' => [
                            'console' => [
                                'name'      => 'suppress',
                                'options'   => [
                                    'suppress' => (PHP_SAPI === 'cli')
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
];
