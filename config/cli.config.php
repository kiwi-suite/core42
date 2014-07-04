<?php
return array(
    'cli' => array(
        'test' => array(
            'name'  => 'test',
            'route' => 'test',
            'description' => 'When executed via the Phar file, performs a self-update by querying the package repository. If successful, it will report the new version.',
            'short_description' => 'Perform a self-update of the script',
        ),
    ),
);
