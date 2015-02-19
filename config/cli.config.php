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
            'description'               => 'Run migrations',
            'short_description'         => 'Run migrations',
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

        'seeding-make' => array(
            'route'                     => 'seeding-make --directory= --name=',
            'command-name'              => 'Core42\Seeding\Make',
            'description'               => 'Create a seeding file into the configured seeding directory',
            'short_description'         => 'Create a seeding file',
            'options_descriptions'      => array(
                '--directory'       => 'directory where seeding will be created',
                '--name'            => 'name of the seeding',
            ),
        ),
        'seeding-list' => array(
            'route'                     => 'seeding-list',
            'command-name'              => 'Core42\Seeding\List',
            'description'               => 'List all seeded and available seeds',
            'short_description'         => 'List of seeds',
        ),
        'seeding-seed' => array(
            'route'                     => 'seeding-seed --name= [-f]',
            'command-name'              => 'Core42\Seeding\Seed',
            'description'               => 'Seed a seeding',
            'short_description'         => 'Seed a seeding',
            'options_descriptions'      => array(
                '--name'           => 'Name of the seeding which should be seeded',
                '-f'               => 'Force the seeding'
            ),
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

        'generate-db-classes' => array(
            'route'                     => 'generate-db-classes --name= --namespace= --directory= --table= [--adapter=]',
            'command-name'              => 'Core42\CodeGenerator\GenerateDbClasses',
            'description'               => 'generates model based on database schema',
            'short_description'         => 'generate model from database',
            'options_descriptions'      => array(
                '--name'                => 'Name of the model or tablegateway (for example \'User\')',
                '--namespace'           => 'Namespace of the classes. (In most cases the same as the module name)',
                '--directory'           => 'Root-Directory of the namespace (eg. module/<modulename>/src/<modulename>)',
                '--table'               => 'tablename where to fetch informations from from',
                '--adapter'             => 'Optional adapter name',
            ),
        ),

        'clear-app-cache' => array(
            'route'                     => 'clear-app-cache',
            'command-name'              => '',
            'description'               => 'Clears config cache and module map cache',
            'short_description'         => 'Clears config cache and module map cache',
        ),
    ),
);
