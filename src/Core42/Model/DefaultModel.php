<?php
namespace Core42\Model;

class DefaultModel extends AbstractModel
{
    private $properties = array();

    public function __call($method, $params)
    {
        $return = null;

        $variableName = substr($method, 3);
        if (strncasecmp($method, "get", 3) === 0) {
            $return = $this->properties[$variableName];
        } else if (strncasecmp($method, "set", 3) === 0) {
            $return = $this;
            $this->properties[$variableName] = $params[0];
        } else {
            throw new \Exception("Method {$method} not found");
        }

        return $return;
    }

    public function exchangeArray($data)
    {
        foreach ($data as $name => $value) {
            $setter = "set".ucfirst($name);
            $this->$setter($value);
        }
    }
}
