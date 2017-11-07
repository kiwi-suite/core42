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

namespace Core42\View\Helper;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

class Auth extends AbstractHelper
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
     * Auth constructor.
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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
     * @throws \Exception
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthenticationService()->hasIdentity();
    }

    /**
     * @throws \Exception
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthenticationService()->getIdentity();
    }

    /**
     * @throws \Exception
     * @return AuthenticationServiceInterface
     */
    protected function getAuthenticationService()
    {
        if (empty($this->authServiceName)) {
            throw new \Exception('authServiceName not set');
        }

        $authService = $this->serviceLocator->get($this->authServiceName);

        if (!($authService instanceof AuthenticationServiceInterface)) {
            throw new \Exception('invalid AuthenticationService');
        }

        return $authService;
    }
}
