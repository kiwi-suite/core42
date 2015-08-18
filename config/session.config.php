<?php
namespace Core42;

return [
    'session_config' => [
        'name' => 'sid',
        'use_trans_sid' => false,
        'use_cookies' => true,
        'use_only_cookies' => true,
    ],

    'session_storage' => [
        'type' => 'Zend\Session\Storage\SessionArrayStorage',
    ],

    'session_manager' => [
        'validator' => [],
    ],
];
