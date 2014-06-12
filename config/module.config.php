<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'params'            => __NAMESPACE__.'\View\Helper\Params',
            'mobileDetect'      => __NAMESPACE__ . '\View\Helper\MobileDetect',
            'acl'               => __NAMESPACE__ . '\View\Helper\Acl',
            'formElementRender' => __NAMESPACE__ . '\View\Helper\FormElementRender',
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
);
