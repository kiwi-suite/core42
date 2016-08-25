<?php
namespace Core42\Permission;

class Role extends \Zend\Permissions\Rbac\Role implements RoleInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = [])
    {
        parent::__construct($name);
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param  string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permission = (string) $permission;
        if (isset($this->permissions[$permission])) {
            return true;
        }

        $permissionParts = explode("/", $permission);

        for ($i = 0; $i < count($permissionParts); $i++) {
            $checkPermission = [];
            for ($j = 0; $j <= $i; $j++) {
                $checkPermission[] = $permissionParts[$j];

                if (isset($this->permissions[implode("/", $checkPermission) . '*'])) {
                    return true;
                }
            }
        }

        $it = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($it as $leaf) {
            /** @var RoleInterface $leaf */
            if ($leaf->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
