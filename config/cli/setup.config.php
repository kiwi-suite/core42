<?php
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
