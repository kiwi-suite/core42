<?php
namespace Core42;

return array(
    'permissions' => array(
        'role_provider_manager' => array(
            'invokables' => array(
                'InMemoryRoleProvider' => 'Core42\Permission\Rbac\Role\InMemoryRoleProvider',
            ),
        ),
        'guard_manager' => array(
            'invokables' => array(
                'RouteGuard' => 'Core42\Permission\Rbac\Guard\RouteGuard',
            ),
        ),
        'assertion_manager' => array(
            'invokables' => array(
                'RouteAssertion' => 'Core42\Permission\Rbac\Assertion\RouteAssertion'
            ),
        ),

        'service' => array(),
    ),
);
