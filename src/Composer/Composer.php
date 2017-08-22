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

namespace Core42\Composer;

use Composer\Script\Event;
use Zend\Json\Json;

class Composer
{
    public static function createComposerInfo(Event $event)
    {
        if (!\is_dir('resources/version')) {
            @\mkdir('resources/version', 0777, true);
        }

        $composer = $event->getComposer();

        $packages = [];
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            $packages[$package->getPrettyName()] = $package->getPrettyVersion();
        }

        \file_put_contents('resources/version/packages.json', Json::encode($packages, false, ['prettyPrint' => true]));
    }
}
