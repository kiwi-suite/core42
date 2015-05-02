<?php
namespace Core42;

return [
    'view_helpers' => [
        'invokables' => [
            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'auth'                  => __NAMESPACE__ . '\View\Helper\Auth',
        ],
        'factories' => [
            'permission'            => 'Core42\View\Helper\Service\PermissionFactory',
            'localization'          => 'Core42\View\Helper\Service\LocalizationFactory',
            'menu'                  => 'Core42\View\Helper\Navigation\Service\MenuFactory',
            'breadcrumbs'           => 'Core42\View\Helper\Navigation\Service\BreadcrumbsFactory',
            'formRender'            => 'Core42\View\Helper\Form\Service\FormRenderFactory',
            'formElementRender'     => 'Core42\View\Helper\Form\Service\FormElementRenderFactory',
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            'permission'    => 'Core42\Mvc\Controller\Plugin\Service\PermissionFactory',
        ],
    ],

    'controllers' => [
        'abstract_factories' => [
            'Core42\Mvc\Controller\Service\ControllerFallbackAbstractFactory'
        ],
    ],
];
