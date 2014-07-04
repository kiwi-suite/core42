<?php
namespace Core42;

return array(
    'log' => array(
        'Log\Dev' => array(
            'writers' => array(
                array(
                    'name' => 'chromephp',
                    'options' => array(
                        'filters' => array(
                            'console' => array(
                                'name'      => 'suppress',
                                'options'   => array(
                                    'suppress' => (PHP_SAPI === 'cli')
                                ),
                            )
                        ),
                    ),
                ),
                array(
                    'name' => 'firephp',
                    'options' => array(
                        'filters' => array(
                            'console' => array(
                                'name'      => 'suppress',
                                'options'   => array(
                                    'suppress' => (PHP_SAPI === 'cli')
                                ),
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),
);
