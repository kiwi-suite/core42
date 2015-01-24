<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'form-theme-delegator'  => __NAMESPACE__ . '\View\Helper\Form\Service\FormThemeDelegator',

            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'auth'                  => __NAMESPACE__ . '\View\Helper\Auth',
            'formRender'            => __NAMESPACE__ . '\View\Helper\Form\FormRender',
            'formElementRender'     => __NAMESPACE__ . '\View\Helper\Form\FormElementRender',
        ),
        'factories' => array(
            'permission'    => 'Core42\View\Helper\Service\PermissionFactory',
            'menu'          => 'Core42\View\Helper\Navigation\Service\MenuFactory',
            'breadcrumbs'   => 'Core42\View\Helper\Navigation\Service\BreadcrumbsFactory'
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
            'permission'    => 'Core42\Mvc\Controller\Plugin\Service\PermissionFactory',
        ),
    ),

    'controllers' => array(
        'abstract_factories' => array(
            'Core42\Mvc\Controller\Service\ControllerFallbackAbstractFactory'
        ),
    ),
);
