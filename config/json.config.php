<?php
namespace Core42;

use Core42\Hydrator\Strategy\Json\DateTimeStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;
use DateTime;

return [
    'json' => [
        'hydrator' => [
            'factories' => [
                DateTimeStrategy::class => InvokableFactory::class,
            ],

            'aliases' => [
                DateTime::class        => DateTimeStrategy::class,
            ],
        ],
    ],
];
