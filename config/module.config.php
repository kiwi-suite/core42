<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'form-theme-delegator'  => __NAMESPACE__ . '\View\Helper\Form\Service\FormThemeDelegator',

            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'mobileDetect'          => __NAMESPACE__ . '\View\Helper\MobileDetect',
            'formRender'            => __NAMESPACE__ . '\View\Helper\Form\FormRender',
            'formElementRender'     => __NAMESPACE__ . '\View\Helper\Form\FormElementRender',
        ),
        'delegators' => array(
            'formRender' => array(
                'form-theme-delegator'
            ),
            'formElementRender' => array(
                'form-theme-delegator'
            ),
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
