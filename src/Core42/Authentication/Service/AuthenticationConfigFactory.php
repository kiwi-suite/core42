<?php
namespace Core42\Authentication\Service;

use Core42\Mvc\TreeRouteMatcher\TreeRouteMatcher;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationConfigFactory implements FactoryInterface
{
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
