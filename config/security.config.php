<?php
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
