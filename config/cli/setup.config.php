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

use Core42\Command\Migration\MigrateCommand;
use Core42\Command\Setup\Command\AssetSetupCommand;
use Core42\Command\Setup\Command\DatabaseSetupCommand;

return [
    'cli_setup_commands' => [
        DatabaseSetupCommand::class,
        MigrateCommand::class,
        AssetSetupCommand::class,
    ],
];
