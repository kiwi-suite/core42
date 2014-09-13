<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42;

use Core42\Console\Console;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    /**
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../../config/module.config.php',
            include __DIR__ . '/../../config/service.config.php',
            include __DIR__ . '/../../config/database.config.php',
            include __DIR__ . '/../../config/session.config.php',
            include __DIR__ . '/../../config/log.config.php',
            include __DIR__ . '/../../config/mail.config.php',
            include __DIR__ . '/../../config/caches.config.php',
            include __DIR__ . '/../../config/cli.config.php',
            include __DIR__ . '/../../config/migration.config.php',
            include __DIR__ . '/../../config/seeding.config.php',
            include __DIR__ . '/../../config/assets.config.php',
            include __DIR__ . '/../../config/permissions.config.php',
            include __DIR__ . '/../../config/form.config.php'
        );
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     * @return array|void
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        if (Console::isConsole()) {
            return;
        }
        $e->getApplication()->getServiceManager()->get('Zend\Session\Service\SessionManager');

        $e->getTarget()->getEventManager()->attach(
            $e->getApplication()->getServiceManager()->get('Core42\Permission\RedirectStrategy')
        );

        $e->getTarget()->getEventManager()->attach(
            $e->getApplication()->getServiceManager()->get('Core42\Permission\UnauthorizedStrategy')
        );
    }
}
