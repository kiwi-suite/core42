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
