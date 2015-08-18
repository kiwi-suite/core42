<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class Params extends AbstractHelper implements ServiceLocatorAwareInterface
{
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
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator()->getServiceLocator();
    }

    /**
     *
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function __invoke($param = null, $default = null)
    {
        if ($param === null) {
            return $this;
        }

        return $this->fromRoute($param, $default);
    }

    /**
     *
     * @param  string                  $name
     * @param  mixed                   $default
     * @return array|\ArrayAccess|null
     */
    public function fromFiles($name = null, $default = null)
    {
        if ($name === null) {
            return $this->getServiceManager()->get('Request')->getFiles($name, $default)->toArray();
        }

        return $this->getServiceManager()->get('Request')->getFiles($name, $default);
    }

    /**
     *
     * @param  string                                 $header
     * @param  mixed                                  $default
     * @return null|\Zend\Http\Header\HeaderInterface
     */
    public function fromHeader($header = null, $default = null)
    {
        if ($header === null) {
            return $this->getServiceManager()->get('Request')->getHeaders($header, $default)->toArray();
        }

        return $this->getServiceManager()->get('Request')->getHeaders($header, $default);
    }

    /**
     *
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromPost($param = null, $default = null)
    {
        if ($param === null) {
            return $this->getServiceManager()->get('Request')->getPost($param, $default)->toArray();
        }

        return $this->getServiceManager()->get('Request')->getPost($param, $default);
    }

    /**
     *
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromQuery($param = null, $default = null)
    {
        if ($param === null) {
            return $this->getServiceManager()->get('Request')->getQuery($param, $default)->toArray();
        }

        return $this->getServiceManager()->get('Request')->getQuery($param, $default);
    }

    /**
     *
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function fromRoute($param = null, $default = null)
    {
        $routeMatch = $this->getServiceManager()->get("Application")->getMvcEvent()->getRouteMatch();
        if (empty($routeMatch)) {
            return "";
        }
        if ($param === null) {
            return $routeMatch->getParams();
        }

        return $routeMatch->getParam($param, $default);
    }
}
