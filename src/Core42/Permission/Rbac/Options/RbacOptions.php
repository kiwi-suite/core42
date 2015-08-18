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

class RbacOptions extends AbstractOptions
{
    /**
     * @var string
     */
    private $identityRoleProvider;

    /**
     * @var string
     */
    private $guestRole = 'guest';

    /**
     * @var array
     */
    private $guards = [];

    /**
     * @var array
     */
    private $roleProvider = [];

    /**
     * @var array
     */
    protected $assertionMap = [];

    /**
     * @var array
     */
    protected $redirectStrategy = [];

    /**
     * @var array
     */
    protected $unauthorizedStrategy = [];

    /**
     * @param  string $identityRoleProvider
     */
    public function setIdentityRoleProvider($identityRoleProvider)
    {
        $this->identityRoleProvider = (string) $identityRoleProvider;
    }

    /**
     * @return string
     */
    public function getIdentityRoleProvider()
    {
        return $this->identityRoleProvider;
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole($guestRole)
    {
        $this->guestRole = (string) $guestRole;
    }

    /**
     * @return string
     */
    public function getGuestRole()
    {
        return $this->guestRole;
    }

    /**
     * @param  array $guards
     */
    public function setGuards(array $guards)
    {
        $this->guards = $guards;
    }

    /**
     * @return array
     */
    public function getGuards()
    {
        return $this->guards;
    }

    /**
     * @param  array $roleProvider
     */
    public function setRoleProvider(array $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }

    /**
     * @return array
     */
    public function getRoleProvider()
    {
        return $this->roleProvider;
    }

    /**
     * @param array $assertionMap
     * @return void
     */
    public function setAssertionMap(array $assertionMap)
    {
        $this->assertionMap = $assertionMap;
    }

    /**
     * @return array
     */
    public function getAssertionMap()
    {
        return $this->assertionMap;
    }

    /**
     * @return array
     */
    public function getRedirectStrategy()
    {
        return $this->redirectStrategy;
    }

    /**
     * @param array $redirectStrategy
     */
    public function setRedirectStrategy($redirectStrategy)
    {
        $this->redirectStrategy = $redirectStrategy;
    }

    /**
     * @return array
     */
    public function getUnauthorizedStrategy()
    {
        return $this->unauthorizedStrategy;
    }

    /**
     * @param array $unauthorizedStrategy
     */
    public function setUnauthorizedStrategy($unauthorizedStrategy)
    {
        $this->unauthorizedStrategy = $unauthorizedStrategy;
    }
}
