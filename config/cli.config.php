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
                '--limit'           => 'Only the given number of pending migrations will be migrated. Default: '
                                            .'All pending migrations will be migrated'
            ),
        ),
        'migration-rollback' => array(
            'route'                     => 'migration-rollback [--limit=]',
            'command-name'              => 'Core42\Migration\Rollback',
            'description'               => 'Rollback the last migrations',
            'short_description'         => 'Rollback the last migrations',
            'options_descriptions'      => array(
                '--limit'           => 'Only the given number of migrated migrations will be rolled back. Default: '
                                            .'The last migration will be rolled back'
            ),
        ),
        'migration-reset' => array(
            'route'                     => 'migration-reset',
            'command-name'              => 'Core42\Migration\Reset',
            'description'               => 'Resets all Migrations. Synonym of migration-rollback with limit=amount '
                                                .'of migrations',
            'short_description'         => 'Resets all Migrations',
        ),

        'development' => array(
            'route'                     => 'development (on|off)',
            'command-name'              => 'Core42\Development\Development',
            'description'               => 'Enables/Disables the development mode. Useful for loading dev-modules or '
                                                .'disabling servicemanager caching',
            'short_description'         => 'Enables/Disables the development mode',
        ),

        'assets' => array(
            'route'                     => 'assets [--copy|-c]',
            'command-name'              => 'Core42\Assets\Assets',
            'description'               => 'Copy or symlink all registered assets into a target directory (for '
                                                .'example public directory) to be accessible over the webserver',
            'short_description'         => 'Copy or symlink all registered assets',
        ),

        'queue-work' => array(
            'route'                     => 'queue-work --queue=',
            'command-name'              => 'Core42\Queue\Work',
            'description'               => 'Starts the Worker for the Queue',
            'short_description'         => 'Starts the Worker for the Queue',
            'options_descriptions'      => array(
                '--queue'           => 'Which queue should be processed',
            ),
        ),

        'queue-listen' => array(
            'route'                     => 'queue-listen --queue= [--sleep=]',
            'command-name'              => 'Core42\Queue\Listen',
            'description'               => 'Listens on the queue and calls the command',
            'short_description'         => 'Listens on the queue and calls the command',
            'defaults'                  => array(
                'sleep'             => 3,
            ),
            'options_descriptions'      => array(
                '--queue'           => 'Which queue should be processed',
                '--sleep'           => 'Number of seconds to wait before polling for new jobs.',
            ),
        ),

        'generate-model' => array(
            'route'                     => 'generate-model --namespace= --directory= [--adapter=] [--overwrite] [--all] [--table=]',
            'command-name'              => 'Core42\CodeGenerator\GenerateModel',
            'description'               => 'generates model based on database schema',
            'short_description'         => 'generate model from database',
        ),
        'generate-tablegateway' => array(
            'route'                     => 'generate-tablegateway --namespace= --directory= [--adapter=] [--overwrite] [--all] [--table=]',
            'command-name'              => 'Core42\CodeGenerator\GenerateTableGateway',
            'description'               => 'generates model based on database schema',
            'short_description'         => 'generate model from database',
        ),
    ),
);
