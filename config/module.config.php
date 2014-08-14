<?php
namespace Core42;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'form-theme-delegator'  => __NAMESPACE__ . '\View\Helper\Form\Service\FormThemeDelegator',

            'params'                => __NAMESPACE__ . '\View\Helper\Params',
            'auth'                  => __NAMESPACE__ . '\View\Helper\Auth',
            'mobileDetect'          => __NAMESPACE__ . '\View\Helper\MobileDetect',
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
            'mobileDetect'  => 'Core42\Mvc\Controller\Plugin\Service\MobileDetectFactory',
            'permission'    => 'Core42\Mvc\Controller\Plugin\Service\PermissionFactory',
        ),
    ),

    'controllers' => array(
        'abstract_factories' => array(
            'Core42\Mvc\Controller\Service\ControllerFallbackAbstractFactory'
        ),
    ),

    'permissions' => array(
        'role_provider_manager' => array(
            'invokables' => array(
                'InMemoryRoleProvider' => 'Core42\Permission\Rbac\Role\InMemoryRoleProvider',
            ),
        ),
        'guard_manager' => array(
            'invokables' => array(
                'RouteGuard' => 'Core42\Permission\Rbac\Guard\RouteGuard',
            ),
        ),
        'assertion_manager' => array(
            'invokables' => array(
                'RouteAssertion' => 'Core42\Permission\Rbac\Assertion\RouteAssertion'
            ),
        ),

        'service' => array(),
    ),
);
