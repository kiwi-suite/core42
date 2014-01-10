<?php
namespace Core42;

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
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
