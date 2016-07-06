<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Options;

use Zend\Stdlib\AbstractOptions;

class RedirectStrategyOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $redirectWhenConnected = false;

    /**
     * @var string
     */
    protected $redirectToRouteConnected = 'home';

    /**
     * @var string
     */
    protected $redirectToRouteDisconnected = 'login';

    /**
     * @var bool
     */
    protected $appendPreviousUri = true;

    /**
     * @var string
     */
    protected $previousUriQueryKey = 'redirectTo';

    /**
     * @param bool $redirectWhenConnected
     * @return void
     */
    public function setRedirectWhenConnected($redirectWhenConnected)
    {
        $this->redirectWhenConnected = (bool) $redirectWhenConnected;
    }

    /**
     * @return bool
     */
    public function getRedirectWhenConnected()
    {
        return $this->redirectWhenConnected;
    }

    /**
     * @param string $redirectToRouteConnected
     * @return void
     */
    public function setRedirectToRouteConnected($redirectToRouteConnected)
    {
        $this->redirectToRouteConnected = (string) $redirectToRouteConnected;
    }

    /**
     * @return string
     */
    public function getRedirectToRouteConnected()
    {
        return $this->redirectToRouteConnected;
    }

    /**
     * @param string $redirectToRouteDisconnected
     * @return void
     */
    public function setRedirectToRouteDisconnected($redirectToRouteDisconnected)
    {
        $this->redirectToRouteDisconnected = (string) $redirectToRouteDisconnected;
    }

    /**
     * @return string
     */
    public function getRedirectToRouteDisconnected()
    {
        return $this->redirectToRouteDisconnected;
    }

    /**
     * @param boolean $appendPreviousUri
     */
    public function setAppendPreviousUri($appendPreviousUri)
    {
        $this->appendPreviousUri = (bool) $appendPreviousUri;
    }

    /**
     * @return boolean
     */
    public function getAppendPreviousUri()
    {
        return $this->appendPreviousUri;
    }

    /**
     * @param string $previousUriQueryKey
     */
    public function setPreviousUriQueryKey($previousUriQueryKey)
    {
        $this->previousUriQueryKey = (string) $previousUriQueryKey;
    }

    /**
     * @return string
     */
    public function getPreviousUriQueryKey()
    {
        return $this->previousUriQueryKey;
    }
}
