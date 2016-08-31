<?php
namespace Core42;

use Core42\View\Helper\Auth;
use Core42\View\Helper\Navigation\Service\BreadcrumbsFactory;
use Core42\View\Helper\Navigation\Service\MenuFactory;
use Core42\View\Helper\Params;
use Core42\View\Helper\Service\AuthFactory;
use Core42\View\Helper\Service\LocalizationFactory;
use Core42\View\Helper\Service\ParamsFactory;
use Core42\View\Helper\Service\PermissionFactory;
use Core42\View\Helper\Uuid;
use Core42\View\Helper\WordTruncate;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_helpers' => [
        'factories' => [
            Params::class           => ParamsFactory::class,
            Auth::class             => AuthFactory::class,
            WordTruncate::class     => InvokableFactory::class,
            Uuid::class             => InvokableFactory::class,
            'permission'            => PermissionFactory::class,
            'localization'          => LocalizationFactory::class,
            'menu'                  => MenuFactory::class,
            'breadcrumbs'           => BreadcrumbsFactory::class,
        ],
        'aliases' => [
            'params' => Params::class,
            'auth'   => Auth::class,
            'wordTruncate' => WordTruncate::class,
            'uuid'   => Uuid::class,
        ],
    ],

    'navigation' => [
        'service_manager' => [],
        'filter_manager' => [],
    ],

    'controller_plugins' => [
        'factories' => [
        ],
    ],

    'controllers' => [
        'abstract_factories' => [
            'Core42\Mvc\Controller\Service\ControllerFallbackAbstractFactory'
        ],
    ],
];
