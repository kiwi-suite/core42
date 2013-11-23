<?php
//THIS FILE IS NOT INCLUDED IN THE CONFIG - ITS JUST A EXAMPLE FILE

return array(
    'acl' => array(
        //default role for unauthenticated users
        'default_unauthenticated_role' => 'guest',
        //default role for authenticated users (used when no role is provided by the identity provider)
        'default_authenticated_role' => 'user',
        // provider to receive the identity of the (authenticated) user
        'identity_provider' => 'Core42\Authentication',
        // authentication service to check if the user ist authenticated
        'authentication_service' => 'Core42\Authentication',
        //provider for roles, priviliges, resources, rules
        'acl_provider' => 'Core42\Permissions\Acl\Provider\ArrayProvider',
        //enabled guards. guards are doing automatic acl checks (for example: check based on requested routes)
        'guards' => array(
            'Core42\Permissions\Acl\Guard\Route' => array(
                'allow_on_no_resource' => false,
            ),
        ),


        'roles' => array(
            'guest' => array(
                'redirect_route' => ''
            ),
            'user' => array(
                'redirect_route' => '',
                'children' => array(
                    'admin' => array(
                        'redirect_route' => '',
                    ),
                ),
            ),
        ),

        'rules' => array(
            'allow' => array(
                array('guest', 'route/admin'),
                array('guest', 'route/admin/login'),

                array('user'),
            ),
        ),
    ),
);

