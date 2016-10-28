<?php
namespace Core42\Composer;

use Composer\Script\Event;
use Zend\Json\Json;

class Composer
{
    public static function createComposerInfo(Event $event)
    {
        if (!is_dir('resources/version')) {
            @mkdir('resources/version', 0777, true);
        }

        $composer = $event->getComposer();

        $packages = [];
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            $packages[$package->getPrettyName()] = $package->getPrettyVersion();
        }

        file_put_contents('resources/version/packages.json', Json::encode($packages, false, ['prettyPrint' => true]));
    }
}
