<?php
namespace Core42;

return [
    'view_helpers' => [
        'invokables' => [
            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'auth'                  => __NAMESPACE__ . '\View\Helper\Auth',
            'wordTruncate'          => __NAMESPACE__ . '\View\Helper\WordTruncate',
        ],
        'factories' => [
            'permission'            => 'Core42\View\Helper\Service\PermissionFactory',
            'localization'          => 'Core42\View\Helper\Service\LocalizationFactory',
            'menu'                  => 'Core42\View\Helper\Navigation\Service\MenuFactory',
            'breadcrumbs'           => 'Core42\View\Helper\Navigation\Service\BreadcrumbsFactory',
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
