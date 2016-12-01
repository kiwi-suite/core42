<?php
namespace Core42;

use Core42\Asset\Hash\DefaultCommitHash;

return [
    'assets' => [
        'asset_url'         => null,
        'asset_path'        => null,
        'prepend_commit'    => false,
        'commit_strategy'   => DefaultCommitHash::class,
        'prepend_base_path' => true,
        'directories'       => [],
    ],
];
