<?php
namespace Core42;

use Core42\Command\Setup\Command\DatabaseSetupCommand;

return [
    'cli_setup_commands' => [
        DatabaseSetupCommand::class
    ],
];
