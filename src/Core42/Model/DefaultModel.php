<?php
namespace Core42\Model;

class DefaultModel extends AbstractModel
{
    /**
     *
     * @var array
     */
    private $properties = array();

    /**
     *
     * @param  string                     $method
     * @param  mixed                      $params
     * @throws \Exception
     * @return \Core42\Model\DefaultModel
     */
    public function __call($method, $params)
    {
        $return = null;

        $variableName = lcfirst(substr($method, 3));
        if (strncasecmp($method, "get", 3) === 0) {
            $return = $this->get($variableName);
        } elseif (strncasecmp($method, "set", 3) === 0) {
            $this->properties[$variableName] = $variableName;

            $return = $this->set($variableName, $params[0]);
        } else {
            throw new \Exception("Method {$method} not found");
        }

        return $return;
    }

    /**
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {
        foreach ($data as $name => $value) {
            $setter = "set".ucfirst($name);
            $this->$setter($value);
        }
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        $this->exchangeArray($data);
    }

    /**
     * @return array
     */
    public function extract()
    {
        $array = array();
        foreach ($this->properties as $variableName) {
            $array[$variableName] = $this->get($variableName);
        }

        return $array;
    }
}
