<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

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
