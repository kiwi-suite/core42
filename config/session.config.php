<?php
namespace Core42;

return array(
    'session_config' => array(
        'name' => 'sid',
        'use_trans_sid' => false,
        'use_cookies' => true,
        'use_only_cookies' => true,
    ),

    'session_storage' => array(
        'type' => 'Zend\Session\Storage\SessionArrayStorage',
    ),

    'session_manager' => array(
        'enable_trans_sid_check' => false,
        'validator' => array(),
    ),
);
