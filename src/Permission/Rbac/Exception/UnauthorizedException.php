<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Permission\Rbac\Exception;

class UnauthorizedException extends \RuntimeException
{
    private $permissionName;

    /**
     * @param string $permissionName
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($permissionName, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->permissionName = $permissionName;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getPermissionName()
    {
        return $this->permissionName;
    }
}
