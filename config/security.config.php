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
    'security' => [
        'csp' => [
            'enable'        => false,
            'nonce'         => false,
            'connect_src'   => false,
            'font_src'      => false,
            'img_src'       => false,
            'media_src'     => false,
            'object_src'    => false,
            'script_src'    => false,
            'style_src'     => false,
            'default_src'   => false,
            'form_action'   => false,
            'form_ancestors'=> false,
            'plugin_types'  => false,
            'child_src'     => false,
            'frame_src'     => false,
            'frame_ancestors'=> false,
        ],
    ],
];
