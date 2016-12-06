<?php
namespace Core42;

use Core42\Command\Assets\AssetsCommand;
use Core42\Command\Cache\ClearCacheCommand;
use Core42\Command\Cache\ListCacheCommand;
use Core42\Command\CodeGenerator\GenerateDbClassesCommand;
use Core42\Command\Config\ConfigGetCommand;
use Core42\Command\Cron\CronCommand;
use Core42\Command\Cron\CronTaskCommand;
use Core42\Command\Development\DevelopmentCommand;
use Core42\Command\Maintenance\MaintenanceCommand;
use Core42\Command\Migration\ListCommand;
use Core42\Command\Migration\MakeCommand;
use Core42\Command\Migration\MigrateCommand;
use Core42\Command\Migration\ResetCommand;
use Core42\Command\Migration\RollbackCommand;
use Core42\Command\Revision\CreateFileCommand;
use Core42\Command\Setup\SetupCommand;

return [
    'cli' => [
        'migration-make' => [
            'group'                     => 'migrations',
            'route'                     => 'migration-make --directory=',
            'command-name'              => MakeCommand::class,
            'description'               => 'Create a migration file into the configured migration directory',
            'short_description'         => 'Create a migration file',
            'options_descriptions'      => [
                '--directory'       => 'directory where migration will be created'
            ]
        ],
        'migration-list' => [
            'group'                     => 'migrations',
            'route'                     => 'migration-list',
            'command-name'              => ListCommand::class,
            'description'               => 'List all migrated and pending migrations',
            'short_description'         => 'List of migrations',
        ],
        'migration-migrate' => [
            'group'                     => 'migrations',
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
            'group'                     => 'migrations',
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
            'group'                     => 'migrations',
            'route'                     => 'migration-reset',
            'command-name'              => ResetCommand::class,
            'description'               => 'Resets all Migrations. Synonym of migration-rollback with limit=amount '
                                                .'of migrations',
            'short_description'         => 'Resets all Migrations',
        ],

        'development' => [
            'group'                     => 'developer',
            'route'                     => 'development (on|off)',
            'command-name'              => DevelopmentCommand::class,
            'description'               => 'Enables/Disables the development mode. Useful for loading dev-modules or '
                                                .'disabling servicemanager caching',
            'short_description'         => 'Enables/Disables the development mode',
        ],

        'setup' => [
            'group'                     => 'setup',
            'route'                     => 'setup',
            'command-name'              => SetupCommand::class,
            'description'               => 'Setup a project',
            'short_description'         => 'Setup a project',
            'development'               => true,
        ],

        'maintenance' => [
            'group'                     => 'setup',
            'route'                     => 'maintenance (on|off)',
            'command-name'              => MaintenanceCommand::class,
            'description'               => 'Enables/Disables maintenance.',
            'short_description'         => 'Enables/Disables maintenance.',
        ],

        'assets' => [
            'group'                     => 'setup',
            'route'                     => 'assets [--copy|-c] [--force|-f]',
            'command-name'              => AssetsCommand::class,
            'description'               => 'Copy or symlink all registered assets into a target directory (for '
                                                .'example public directory) to be accessible over the webserver',
            'short_description'         => 'Copy or symlink all registered assets',
            'options_descriptions'      => [
                '--copy|-c'             => 'Copy all files instead of a symlink',
                '--force|-f'            => 'Override when target folder already exists',
            ],
        ],

        'revision-file-create' => [
            'group'                     => 'setup',
            'route'                     => 'revision-file-create',
            'command-name'              => CreateFileCommand::class,
            'description'               => 'Creates a revision file',
            'short_description'         => 'Creates a revision file',
        ],

        'generate-db-classes' => [
            'group'                     => 'developer',
            'route'                     => 'generate-db-classes --namespace= --directory= [--name=] [--table=] [--all=] [--adapter=] [--getter-setter] [--overwrite]',
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
                '--overwrite'           => 'overwrites existing files instead of merging their content',
            ],
            'development'               => true,
        ],

        'cache-clear' => [
            'group'                     => 'cache',
            'route'                     => 'cache-clear [<cache>] [--all|-a]',
            'command-name'              => ClearCacheCommand::class,
            'description'               => 'Clear selected cache',
            'short_description'         => 'Clear selected cache',
            'options_descriptions'      => [
                'cache'                => 'clear selected cache',
                '--all|-a'             => 'clear all caches',
            ],
        ],

        'cache-list' => [
            'group'                     => 'cache',
            'route'                     => 'cache-list',
            'command-name'              => ListCacheCommand::class,
            'description'               => 'List all available caches',
            'short_description'         => 'List all available caches',
        ],

        'cron' => [
            'group'                     => 'cron',
            'route'                     => '[<name>] [--ignorelock|-i] [--group=]',
            'command-name'              => CronCommand::class,
            'description'               => 'Start the cron tasks',
            'short_description'         => 'Start the cron tasks',
        ],

        'cron-task' => [
            'group'                     => 'cron',
            'route'                     => '<name> [--ignorelock|-i]',
            'command-name'              => CronTaskCommand::class,
            'description'               => 'Start a single cron task',
            'short_description'         => 'Start a single cron task',
        ],

        'config' => [
            'group'                     => 'setup',
            'route'                     => '[<key>]',
            'command-name'              => ConfigGetCommand::class,
            'description'               => 'Display config information on a given config key',
            'short_description'         => 'Display config information',
            'options_descriptions'      => [
                'key'                => 'config key. Subkeys are possible through a '.' separator',
            ],
        ],
    ],
];
