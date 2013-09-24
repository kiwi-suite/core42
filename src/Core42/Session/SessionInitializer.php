<?php
namespace Core42\Session;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class SessionInitializer
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var bool
     */
    private $enableTransSidCheck = false;

    /**
     * @var bool
     */
    private $enabledTransSid = false;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function initialize(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $sessionManager = $serviceLocator->get("Zend\Session\Service\SessionManagerFactory");
        $config = $serviceLocator->get("Config");
        if (isset($config['session_manager']['validator'])) {
            $chain = $sessionManager->getValidatorChain();
            foreach ($config['session_manager']['validator'] as $validator) {
                $validator = new $validator();
                $chain->attach('session.validate', array($validator, 'isValid'));
            }
        }

        if (isset($config['session_manager']['enable_trans_sid_check'])) {
            $this->enableTransSidCheck = (boolean) $config['session_manager']['enable_trans_sid_check'];
        }

        if ($this->enableTransSidCheck === true) {
            $this->checkForCookieHttpParam();
            $serviceLocator->get('Application')->getEventManager()->attach(MvcEvent::EVENT_FINISH, array($this, 'registerSidInHeaderLocation'));
            $sessionManager->start();
        }
    }

    /**
     * @param bool $enable
     */
    private function transSid($enable)
    {
        if ($this->enableTransSidCheck === false) {
            return;
        }

        $this->enabledTransSid = $enable;
        $sessionManager = $this->serviceLocator->get("Zend\Session\Service\SessionManagerFactory");

        $config = $sessionManager->getConfig();
        if ($enable === true) {
            $config->setStorageOption('use_trans_sid', true);
            $config->setStorageOption('use_cookies', false);
            $config->setStorageOption('use_only_cookies', false);
        } else {
            $config->setStorageOption('use_trans_sid', false);
            $config->setStorageOption('use_cookies', true);
            $config->setStorageOption('use_only_cookies', true);
        }

    }

    /**
     *
     */
    public function checkForCookieHttpParam()
    {
        $request = $this->serviceLocator->get('Application')->getRequest();
        $sessionManager = $this->serviceLocator->get("Zend\Session\Service\SessionManagerFactory");

        if ($request instanceof \Zend\Http\Request) {
            if ($request->getQuery($sessionManager->getName(), null) !== null || $request->getPost($sessionManager->getName(), null) !== null) {
                $this->transSid(true);
            } else {
                $this->transSid(false);
            }
        }
    }

    /**
     * @param MvcEvent $event
     */
    public function registerSidInHeaderLocation(MvcEvent $event)
    {
        if ($this->enabledTransSid === false) {
            return;
        }

        $response = $event->getResponse();
        if ($response instanceof \Zend\Http\Response && $response->isRedirect()) {
            $sessionManager = $this->serviceLocator->get("Zend\Session\Service\SessionManagerFactory");

            $uri = $response->getHeaders()->get('Location')->uri();
            if (is_string($uri)) {
                $uri = UriFactory::factory($uri);
            }

            $params = $uri->getQueryAsArray();
            $params[$sessionManager->getName()] = $sessionManager->getId();
            $uri->setQuery($params);
            $response->getHeaders()->get('Location')->setUri($uri);
        }
    }
}
