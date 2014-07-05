<?php
namespace Core42;
return array(
    'migration' => array(
        'directory'     => array(
            __NAMESPACE__ => 'data/migrations',
        ),
        'db_adapter'    => 'Db\Master',
        'table_name'    => 'migrations',
    ),
);
