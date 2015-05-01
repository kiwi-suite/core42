<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'auth'                  => __NAMESPACE__ . '\View\Helper\Auth',
        ),
        'factories' => array(
            'permission'            => 'Core42\View\Helper\Service\PermissionFactory',
            'localization'          => 'Core42\View\Helper\Service\LocalizationFactory',
            'menu'                  => 'Core42\View\Helper\Navigation\Service\MenuFactory',
            'breadcrumbs'           => 'Core42\View\Helper\Navigation\Service\BreadcrumbsFactory',
            'formRender'            => 'Core42\View\Helper\Form\Service\FormRenderFactory',
            'formElementRender'     => 'Core42\View\Helper\Form\Service\FormElementRenderFactory',
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
