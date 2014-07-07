<?php
return array(
    'cli' => array(
        'migration-make' => array(
            'route'                     => 'migration-make --directory=',
            'command-name'              => 'Core42\Migration\Make',
            'description'               => 'Create a migration file into the configured migration directory',
            'short_description'         => 'Create a migration file',
            'options_descriptions'      => array(
                '--directory'       => 'directory where migration will be created'
            ),
        ),
        'migration-list' => array(
            'route'                     => 'migration-list',
            'command-name'              => 'Core42\Migration\List',
            'description'               => 'List all migrated and pending migrations',
            'short_description'         => 'List of migrations',
        ),
        'migration-migrate' => array(
            'route'                     => 'migration-migrate [--limit=]',
            'command-name'              => 'Core42\Migration\Migrate',
            'description'               => 'Create a migration file into the configured migration directory',
            'short_description'         => 'Create a migration file',
            'options_descriptions'      => array(
                '--limit'           => 'Only the given number of pending migrations will be migrated. Default: All pending migrations will be migrated'
            ),
        ),
        'migration-rollback' => array(
            'route'                     => 'migration-rollback [--limit=]',
            'command-name'              => 'Core42\Migration\Rollback',
            'description'               => 'Rollback the last migrations',
            'short_description'         => 'Rollback the last migrations',
            'options_descriptions'      => array(
                '--limit'           => 'Only the given number of migrated migrations will be rolled back. Default: The last migration will be rolled back'
            ),
        ),
        'migration-reset' => array(
            'route'                     => 'migration-reset',
            'command-name'              => 'Core42\Migration\Reset',
            'description'               => 'Resets all Migrations. Synonym of migration-rollback with limit=amount of migrations',
            'short_description'         => 'Resets all Migrations',
        ),
    ),
);
