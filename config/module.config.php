<?php
namespace Core42;

use Zend\Db\Adapter\Adapter;
return array(
    'service_manager' => array(
        'factories' => array(
            'db_master' => function($serviceManager) {
                $config = $serviceManager->get('Config');
                if (!isset($config['dbParams'])) {
                    throw new \Exception("no db config params");
                }
                return new Adapter($config['dbParams']);
            },
        ),
    ),
);
