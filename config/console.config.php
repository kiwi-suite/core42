<?php
namespace Core42;

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'migration-make' => array(
                    'options' => array(
                        'route'    => 'migration-make',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'make'
                        ),
                    ),
                ),
                'migration-migrate' => array(
                    'options' => array(
                        'route'    => 'migration-migrate',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'migrate'
                        ),
                    ),
                ),
                'migration-rollback' => array(
                    'options' => array(
                        'route'    => 'migration-rollback',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'rollback'
                        ),
                    ),
                ),
                'migration-reset' => array(
                    'options' => array(
                        'route'    => 'migration-reset',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Migration',
                            'action' => 'reset'
                        ),
                    ),
                ),

                'seeding-make' => array(
                    'options' => array(
                        'route'    => 'seeding-make <name>',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'make'
                        ),
                    ),
                ),
                'seeding-seed' => array(
                    'options' => array(
                        'route'    => 'seeding-seed [<name>]',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'seed'
                        ),
                    ),
                ),
                'seeding-reset' => array(
                    'options' => array(
                        'route'    => 'seeding-reset [<name>]',
                        'defaults' => array(
                            'controller' => __NAMESPACE__.'\Controller\Cli\Seeding',
                            'action' => 'reset'
                        ),
                    ),
                ),
            ),
        ),
    ),
);
