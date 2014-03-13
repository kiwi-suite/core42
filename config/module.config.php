<?php
namespace Core42;

return array(
    'service_manager_static_aware' => array(
        'SqlQuery' => 'Core42\Db\SqlQuery\SqlQuery',
    ),

    'view_helpers' => array(
        'invokables' => array(
            'params' => __NAMESPACE__.'\View\Helper\Params',
            'mobileDetect' => __NAMESPACE__ . '\View\Helper\MobileDetect',
            'valueManager' => __NAMESPACE__ . '\View\Helper\ValueManager',
            'acl' => __NAMESPACE__ . '\View\Helper\Acl',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'partial/value-manager/input' => __DIR__ . '/../view/partial/value-manager/input.phtml',
        ),
    ),

    'controller_plugins' => array(
        'factories' => array(
            'mobileDetect' => 'Core42\Mvc\Controller\Plugin\Service\MobileDetectFactory',
        ),
    ),

    'controllers' => array(
        'abstract_factories' => array(
            'Core42\Controller\Service\ControllerFallbackAbstractFactory'
        ),
    ),

//    'authentication' => array(
//        'default' => array(
//            'plugins' => array(
//                'Authentication\Plugin\TableGateway' => array(
//                    'name' => 'Core42\Authentication\Plugin\TableGateway',
//                    'options' => array(
//                        'table_gateway' => 'Core42\UserTableGateway',
//                        'identity_column' => 'username',
//                        'credential_column' => 'password',
//                    ),
//                ),
//            ),
//            'adapter' => 'Authentication\Plugin\TableGateway',
//            'storage' => 'Authentication\Plugin\TableGateway',
//        ),
//        'routes' => array(
//            'route/you/want' => 'default',
//        ),
//    ),
);
