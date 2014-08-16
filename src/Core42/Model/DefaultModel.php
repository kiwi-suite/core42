<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
}
