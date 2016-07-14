<?php
namespace Core42;

use Core42\Command\Assets\AssetsCommand;
use Core42\Command\Cache\ClearAppCacheCommand;
use Core42\Command\CodeGenerator\GenerateDbClassesCommand;
use Core42\Command\CodeGenerator\GenerateModuleCommand;
use Core42\Command\Cron\CronCommand;
use Core42\Command\Cron\CronWrapperCommand;
use Core42\Command\Development\DevelopmentCommand;
use Core42\Command\Maintenance\MaintenanceCommand;
use Core42\Command\Migration\ListCommand;
use Core42\Command\Migration\MakeCommand;
use Core42\Command\Migration\MigrateCommand;
use Core42\Command\Migration\ResetCommand;
use Core42\Command\Migration\RollbackCommand;

return [
    'cli' => [
        'migration-make' => [
            'route'                     => 'migration-make --directory=',
            'command-name'              => MakeCommand::class,
            'description'               => 'Create a migration file into the configured migration directory',
            'short_description'         => 'Create a migration file',
            'options_descriptions'      => [
                '--directory'       => 'directory where migration will be created'
            ]
        ],
        'migration-list' => [
            'route'                     => 'migration-list',
            'command-name'              => ListCommand::class,
            'description'               => 'List all migrated and pending migrations',
            'short_description'         => 'List of migrations',
        ],
        'migration-migrate' => [
            'route'                     => 'migration-migrate [--limit=]',
            'command-name'              => MigrateCommand::class,
            'description'               => 'Run migrations',
            'short_description'         => 'Run migrations',
            'options_descriptions'      => [
                '--limit'           => 'Only the given number of pending migrations will be migrated. Default: '
                                            .'All pending migrations will be migrated'
            ]
        ],
        'migration-rollback' => [
            'route'                     => 'migration-rollback [--limit=]',
            'command-name'              => RollbackCommand::class,
            'description'               => 'Rollback the last migrations',
            'short_description'         => 'Rollback the last migrations',
            'options_descriptions'      => [
                '--limit'           => 'Only the given number of migrated migrations will be rolled back. Default: '
                                            .'The last migration will be rolled back'
            ]
        ],
        'migration-reset' => [
            'route'                     => 'migration-reset',
            'command-name'              => ResetCommand::class,
            'description'               => 'Resets all Migrations. Synonym of migration-rollback with limit=amount '
                                                .'of migrations',
            'short_description'         => 'Resets all Migrations',
        ],

        'development' => [
            'route'                     => 'development (on|off)',
            'command-name'              => DevelopmentCommand::class,
            'description'               => 'Enables/Disables the development mode. Useful for loading dev-modules or '
                                                .'disabling servicemanager caching',
            'short_description'         => 'Enables/Disables the development mode',
        ],

        'maintenance' => [
            'route'                     => 'maintenance (on|off)',
            'command-name'              => MaintenanceCommand::class,
            'description'               => 'Enables/Disables maintenance.',
            'short_description'         => 'Enables/Disables maintenance.',
        ],

        'assets' => [
            'route'                     => 'assets [--copy|-c]',
            'command-name'              => AssetsCommand::class,
            'description'               => 'Copy or symlink all registered assets into a target directory (for '
                                                .'example public directory) to be accessible over the webserver',
            'short_description'         => 'Copy or symlink all registered assets',
        ],

        'generate-db-classes' => [
            'route'                     => 'generate-db-classes --namespace= --directory= [--name=] [--table=] [--all=] [--adapter=] [--getter-setter]',
            'command-name'              => GenerateDbClassesCommand::class,
            'description'               => 'generates model based on database schema',
            'short_description'         => 'generate model from database',
            'options_descriptions'      => [
                '--name'                => 'Name of the model or tablegateway (for example \'User\')',
                '--namespace'           => 'Namespace of the classes. (In most cases the same as the module name)',
                '--directory'           => 'Root-Directory of the namespace (eg. module/<modulename>/src/<modulename>)',
                '--table'               => 'tablename where to fetch informations from from',
                '--all'                 => 'generate db classes for all tables with the given prefix',
                '--adapter'             => 'Optional adapter name',
                '--getter-setter'       => 'Generate Getter & Setter instead of PphDoc',
            ],
            'development'               => true,
        ],

        'generate-module' => [
            'route'                     => 'generate-module --name=',
            'command-name'              => GenerateModuleCommand::class,
            'description'               => 'generates a module skeleton directory structure',
            'short_description'         => 'generate a module',
            'options_descriptions'      => [
                '--name'                => 'Name of the module in CamelCase (Namespace)',
            ],
            'development'               => true,
        ],

        'clear-app-cache' => [
            'route'                     => 'clear-app-cache',
            'command-name'              => ClearAppCacheCommand::class,
            'description'               => 'Clears config cache and module map cache',
            'short_description'         => 'Clears config cache and module map cache',
        ],

        'cron' => [
            'route'                     => '[<name>] [--ignorelock|-i] [--silent] [--group=]',
            'command-name'              => CronCommand::class,
            'description'               => 'Start the cron tasks',
            'short_description'         => 'Start the cron tasks',
        ],

        'cron-wrapper' => [
            'route'                     => '<name> [--ignorelock|-i] [--silent]',
            'command-name'              => CronWrapperCommand::class,
            'description'               => 'Start a single cron task',
            'short_description'         => 'Start a single cron task',
        ],
    ],
];
