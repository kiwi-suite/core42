<?php
namespace Core42;

use Core42\Permission\Rbac\Assertion\RouteAssertion;
use Core42\Permission\Rbac\Guard\RouteGuard;
use Core42\Permission\Rbac\Role\InMemoryRoleProvider;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'permissions' => [
        'role_provider_manager' => [
            'factories' => [
                InMemoryRoleProvider::class => InvokableFactory::class,
            ],
            'aliases' => [
                'InMemoryRoleProvider' => InMemoryRoleProvider::class
            ],
        ],
        'guard_manager' => [
            'factories' => [
                RouteGuard::class => InvokableFactory::class,
            ],
            'aliases' => [
                'RouteGuard' => RouteGuard::class
            ],
        ],
        'assertion_manager' => [
            'factories' => [
                RouteAssertion::class => InvokableFactory::class,
            ],
            'aliases' => [
                'RouteAssertion' => RouteAssertion::class,
            ],
        ],

        'service' => [],
    ],
];
