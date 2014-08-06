<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'params'            => __NAMESPACE__.'\View\Helper\Params',
            'mobileDetect'      => __NAMESPACE__ . '\View\Helper\MobileDetect',
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
);
