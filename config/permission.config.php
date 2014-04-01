<?php
namespace Core42;

return array(
    'permission' => array(
        //whether enable or disable the permissions
        'enabled' => false,
        //default role for unauthenticated users
        'default_unauthenticated_role' => '',
        //default role for authenticated users (used when no role is provided by the identity provider)
        'default_authenticated_role' => '',

        // provider to receive the identity of the (authenticated) user
        'identity_provider' => '',
        // authentication service to check if the user ist authenticated
        'authentication_service' => '',

        'role_provider' => '',

        'guards' => array(),
    ),
);
