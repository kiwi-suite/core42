<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42;

use Core42\View\Helper\Auth;
use Core42\View\Helper\InlineScript;
use Core42\View\Helper\Navigation\Service\MenuFactory;
use Core42\View\Helper\Params;
use Core42\View\Helper\Service\AssetUrlFactory;
use Core42\View\Helper\Service\AuthFactory;
use Core42\View\Helper\Service\CspFactory;
use Core42\View\Helper\Service\LocalizationFactory;
use Core42\View\Helper\Service\MobileDetectFactory;
use Core42\View\Helper\Service\ParamsFactory;
use Core42\View\Helper\Service\PermissionFactory;
use Core42\View\Helper\Service\SlugifyFactory;
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
            'assetUrl'              => AssetUrlFactory::class,
            'permission'            => PermissionFactory::class,
            'localization'          => LocalizationFactory::class,
            'menu'                  => MenuFactory::class,
            'mobileDetect'          => MobileDetectFactory::class,
            'slugify'               => SlugifyFactory::class,
            'csp'                   => CspFactory::class,

            InlineScript::class     => InvokableFactory::class,

        ],
        'aliases' => [
            'params' => Params::class,
            'auth'   => Auth::class,
            'wordTruncate' => WordTruncate::class,
            'uuid'   => Uuid::class,

            'inlinescript'          => InlineScript::class,
            'inlineScript'          => InlineScript::class,
            'InlineScript'          => InlineScript::class,
        ],
    ],
];
