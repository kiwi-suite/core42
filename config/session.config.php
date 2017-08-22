<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
