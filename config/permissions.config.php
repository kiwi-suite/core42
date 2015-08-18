<?php
namespace Core42;

return [
    'permissions' => [
        'role_provider_manager' => [
            'invokables' => [
                'InMemoryRoleProvider' => 'Core42\Permission\Rbac\Role\InMemoryRoleProvider',
            ],
        ],
        'guard_manager' => [
            'invokables' => [
                'RouteGuard' => 'Core42\Permission\Rbac\Guard\RouteGuard',
            ],
        ],
        'assertion_manager' => [
            'invokables' => [
                'RouteAssertion' => 'Core42\Permission\Rbac\Assertion\RouteAssertion'
            ],
        ],

        'service' => [],
    ],
];
