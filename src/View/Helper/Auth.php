<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Auth extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var string
     */
    private $authServiceName;

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return null
     * @throws \Exception
     */
    public function getServiceLocator()
    {
        throw new \Exception("I don't want this?!?!?!");
    }

    /**
     * @param string $authServiceName
     * @return $this
     */
    public function __invoke($authServiceName)
    {
        $this->authServiceName = $authServiceName;

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasIdentity()
    {
        return $this->getAuthenticationService()->hasIdentity();
    }

    /**
     * @return mixed|null
     * @throws \Exception
     */
    public function getIdentity()
    {
        return $this->getAuthenticationService()->getIdentity();
    }

    /**
     * @return AuthenticationServiceInterface
     * @throws \Exception
     */
    protected function getAuthenticationService()
    {
        if (empty($this->authServiceName)) {
            throw new \Exception("authServiceName not set");
        }

        $authService = $this->serviceLocator->getServiceLocator()->get($this->authServiceName);

        if (!($authService instanceof AuthenticationServiceInterface)) {
            throw new \Exception("invalid AuthenticationService");
        }

        return $authService;
    }
}
