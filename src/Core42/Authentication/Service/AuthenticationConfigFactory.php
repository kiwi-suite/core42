<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Authentication\Service;

use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationConfigFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $configkey = 'authentication';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $config = $serviceLocator->get('Config');

        if (!isset($config[$this->configkey]) || empty($config[$this->configkey])) {
            return array();
        }

        /** @var TreeRouteMatcher $treeRouteMatcher */
        $treeRouteMatcher = $serviceLocator->get('TreeRouteMatcher');
        $authName = $treeRouteMatcher->getConfigKey($config[$this->configkey]['routes']);

        return $config[$this->configkey]['auth'][$authName];
    }
}
