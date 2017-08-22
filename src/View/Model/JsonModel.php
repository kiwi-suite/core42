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
