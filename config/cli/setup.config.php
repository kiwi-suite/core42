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
