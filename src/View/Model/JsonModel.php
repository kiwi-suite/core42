<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Model;

use Zend\View\Model\JsonModel as ZendJsonModel;

class JsonModel extends ZendJsonModel
{
    /**
     * The encoded JSON string
     *
     * @var string
     */
    protected $jsonString = null;

    /**
     * Set the already encoded JSON string
     *
     * @param  string    $json
     * @return JsonModel
     */
    public function setJsonString($json)
    {
        $this->jsonString = $json;

        return $this;
    }

    /**
     * Serialize to JSON
     *
     * @return string
     */
    public function serialize()
    {
        //todo: merge
        if ($this->jsonString !== null) {
            return $this->jsonString;
        }

        return parent::serialize();
    }
}
