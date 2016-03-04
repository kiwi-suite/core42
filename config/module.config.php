<?php
namespace Core42;

use Core42\View\Helper\Auth;
use Core42\View\Helper\Navigation\Service\BreadcrumbsFactory;
use Core42\View\Helper\Navigation\Service\MenuFactory;
use Core42\View\Helper\Params;
use Core42\View\Helper\Service\LocalizationFactory;
use Core42\View\Helper\Service\PermissionFactory;
use Core42\View\Helper\WordTruncate;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_helpers' => [
        'factories' => [
            Params::class           => InvokableFactory::class,
            Auth::class             => InvokableFactory::class,
            WordTruncate::class     => InvokableFactory::class,
            'permission'            => PermissionFactory::class,
            'localization'          => LocalizationFactory::class,
            'menu'                  => MenuFactory::class,
            'breadcrumbs'           => BreadcrumbsFactory::class,
        ],
        'aliases' => [
            'params' => Params::class,
            'auth'   => Auth::class,
            'wordTruncate' => WordTruncate::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            'permission'    => \Core42\Mvc\Controller\Plugin\Service\PermissionFactory::class,
        ],
    ],

    'controllers' => [
        'abstract_factories' => [
            'Core42\Mvc\Controller\Service\ControllerFallbackAbstractFactory'
        ],
    ],
];
