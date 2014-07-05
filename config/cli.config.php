<?php
return array(
    'cli' => array(
        'migration-make' => array(
            'route' => 'migration-make --directory=',
            'command-name' => 'Core42\Migration\Make',
            'description' => 'Create a migration file into the configured migration directory',
            'short_description' => 'Create a migration file',
            'options_descriptions' => array(
                '--directory' => 'directory where migration will be created'
            ),
        ),
        'migration-migrate' => array(
            'route' => 'migration-migrate',
            'command-name' => 'Core42\Migration\Migrate',
            'description' => 'Create a migration file into the configured migration directory',
            'short_description' => 'Create a migration file',
        ),
    ),
);
